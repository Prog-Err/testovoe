<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Persistence\ManagerRegistry;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends AbstractController
{
    #[Route('/api/home', name: 'app_home', methods: ['POST', 'OPTIONS'])]
    public function index(Request $request,  ManagerRegistry $doctrine): Response
    {
        $tableData = [];
        $attend = 0;
        $absence = 0;

        $input = $request->toArray();
        $fio = $input['fio'] ?? "";
        $company = $input['company'] ?? "";
        $attend = $input['attend'] ?? -1;
        $arr = [];

        if ($attend == 1) {
            $arr[] = " attend=1";
        }
        if ($attend == 0) {
            $arr[] = " attend=0";
        }
        if (!empty($fio)) {
            $arr[] = " fio like '%$fio%'";
        }
        if (!empty($company)) {
            $arr[] = " company like '%$company%'";
        }

        $sql = 'SELECT id,fio,company,grop as `group`,attend from user ';
        $sqlCount = 'SELECT count(id) from user ';
        $where = "";
        if (!empty($arr)) {
            $where = " WHERE " . implode(" AND ", $arr);
        }
        $db = $doctrine->getConnection('default');
        $tableData = $db->prepare($sql . $where)->executeQuery()->fetchAllAssociative();
        foreach ($tableData as $k => $v) {
            $tableData[$k]['attend'] = (bool)$v['attend'];
        }
        if (empty($where)) {
            $where = " WHERE";
        } else {
            $where .= " AND ";
        }
        $attend = $db->prepare($sqlCount . $where . " attend=1")->executeQuery()->fetchOne();
        $absence = $db->prepare($sqlCount . $where . " attend=0")->executeQuery()->fetchOne();

        return $this->json([
            'ok' => true,
            'tableData' => $tableData,
            'attend' => $attend,
            'absence' => $absence,
        ]);
    }

    #[Route('/api/submit', name: 'app_add', methods: ['POST', 'OPTIONS'])]
    public function submit(Request $request,  ManagerRegistry $doctrine): Response
    {
        $input = $request->toArray();

        $data = $input['data'] ?? [];
        $id = $data['id'] ?? 0;
        $fio = $data['fio'] ?? "";
        $company = $data['company'] ?? "";
        $group = $data['group'] ?? -1;
        $attend = $data['attend'] ?? false;
        if (empty($fio)) {
            return $this->json([
                'ok' => false,
                'error' => 'Заполните поле ФИО'
            ]);
        }
        if ($group < 0) {
            return $this->json([
                'ok' => false,
                'error' => 'Заполните поле группы'
            ]);
        }
        $em = $doctrine->getManager('default');

        if ($id == 0)
            $user = new User();
        else
            $user = $doctrine->getRepository(User::class, 'default')->find($id);
        $user->setFio($fio);
        $user->setCompany($company);
        $user->setGrop($group);
        $user->setAttend($attend);
        $em->persist($user);
        $em->flush();

        return $this->json([
            'ok' => true,
        ]);
    }


    #[Route('/api/del', name: 'app_del', methods: ['POST', 'OPTIONS'])]
    public function del(Request $request,  ManagerRegistry $doctrine): Response
    {
        $input = $request->toArray();
        $id = $input['id'] ?? -1;
        $em = $doctrine->getManager('default');
        $user = $doctrine->getRepository(User::class, 'default')->find($id);
        $em->remove($user);
        $em->flush();
        return $this->json([
            'ok' => true,
        ]);
    }
}
