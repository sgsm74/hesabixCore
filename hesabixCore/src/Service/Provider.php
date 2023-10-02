<?php

namespace App\Service;

use App\Entity\Business;
use App\Entity\PlugNoghreOrder;
use App\Entity\PrinterQueue;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\Writer\Exception;
use ReflectionFunction;
use Symfony\Component\HttpFoundation\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class Provider
{
    protected EntityManagerInterface $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function createSearchParams(Request $request){
        $response = [];
        $params = [];
        if ($content = $request->getContent()) {
            $params = json_decode($content, true);
        }

        //set page of content want to search
        $page = 1;
        if(array_key_exists('page',$params))
            $page = $params['page'];
        $response['page'] = $page;

        $cat = '';
        if(array_key_exists('cat',$params))
            $cat = $params['cat'];
        $response['cat'] = $cat;

        //set max Count of content want to search
        $count = 15;
        if(array_key_exists('count',$params))
            $count = $params['count'];
        $response['count'] = $count;

        //set search keyword of content
        $search = '';
        if(array_key_exists('key',$params))
            $search = $params['key'];
        $response['key'] = $search;

        return $response;
    }

    public function maxPages($params,$rowsAllCount){
        $res =  $rowsAllCount / $params['count'];
        return is_float($res) ? (int)$res+1:$res;
    }
    public function maxPagesWithPageCount($count,$rowsAllCount){
        $res =  $rowsAllCount / $count;
        return is_float($res) ? (int)$res+1:$res;
    }
    public function gravatarHash($email){
        return md5( strtolower( trim( $email) ) );
    }

    public function getAccountingCode($bid,$part){
        $setter = 'set' . ucfirst($part). 'Code';
        $part = 'get' . ucfirst($part). 'Code';

        $business = $this->entityManager->getRepository(Business::class)->find($bid);
        if(!$business)
            return false;
        $count = $business->{$part}();
        if(is_null($count))
            $count = 1000;
        $business->{$setter}(intval($count) + 1);
        $this->entityManager->persist($business);
        $this->entityManager->flush();
        return $count;
    }

    /**
     * @throws \ReflectionException
     */
    public function Entity2Array($entity, int $deep = 1, array $ignores = []): null|array{
        if(is_null($entity)) return [];
        $result = [];
        $methods = get_class_methods($entity);
        $getMethods = [];
        //get getter methods
        foreach ($methods as $method){
            if(str_starts_with($method, 'get')){
                $getMethods[] = trim(trim($method));
            }
        }
        //var_dump($getMethods);
        foreach ($getMethods as $method){
            if(!is_int(array_search(lcfirst(trim(str_replace('get', '', $method))), $ignores))){
                if(method_exists($entity,$method) && $method != 'get'){
                    $method = trim(trim($method));
                    $canProced = true;
                    $reflection = new \ReflectionMethod($entity, $method);
                    if ($reflection->isPublic() && !str_starts_with('\\0',$method)) {
                        $value = $entity->$method(); 
                    }
                    else
                        $canProced = false;
                    if($canProced){
                        if(!is_object($value)){
                            $result[lcfirst(str_replace('get','',$method))] = $value;
                        }
                        else{
                            if($deep != 0){
                                $result[lcfirst(str_replace('get','',$method))] = $this->Entity2Array($value,$deep - 1);
                            }
                        }  
                    }
                   
                }

            }
        }
        return $result;
    }

    public function ArrayEntity2Array(array $entity, int $deep = 1, array $ignores = []): null|array{
        if(count($entity) == 0) return [];
        $result = [];
        foreach ($entity as $item){
            $result[] = $this->Entity2Array($item,$deep,$ignores);
        }
        return $result;
    }

    public function createPrint(Business $bid,User $user,String $data){
        $print = new PrinterQueue();
        $print->setDateSubmit(time());
        $print->setSubmitter($user);
        $print->setBid($bid);
        $print->setView($data);
        $print->setPid($this->RandomString(128));
        $this->entityManager->persist($print);
        $this->entityManager->flush();
        return $print->getPid();
    }

    /**
     * @throws Exception
     */
    public function createExcell(array $entities, array $ignores = [],array $headers = null){

        $spreadsheet = new Spreadsheet();
        $activeWorksheet = $spreadsheet->getActiveSheet();
        $arrayEntity = $this->ArrayEntity2Array($entities,0,$ignores);
        $activeWorksheet->fromArray($arrayEntity,null,'A1');
        $activeWorksheet->setRightToLeft(true);
        $activeWorksheet->getHeaderFooter()->setOddHeader('&CHeader of the Document');
        $writer = new Xlsx($spreadsheet);
        $filePath = __DIR__ . '/../../var/'.$this->RandomString(12).'.xlsx';
        $writer->save($filePath);
        return $filePath;
    }
    /**
     * function to generate random strings
     * @param 		int 	$length 	number of characters in the generated string
     * @return 		string	a new string is created with random characters of the desired length
     */
    private function RandomString($length = 32) {
        return substr(str_shuffle(str_repeat($x='23456789ABCDEFGHJKLMNPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
    }
}