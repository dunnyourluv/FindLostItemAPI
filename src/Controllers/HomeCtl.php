<?php

namespace DUVX\Controllers;

use DunnServer\MVC\Controller;
use DunnServer\Utils\DunnArray;
use DunnServer\Utils\DunnMap;

class HomeCtl extends Controller
{
  function doGet($req, $res)
  {
    $map = new DunnMap();
    $map->set('team_name', 'DUVX');
    $map->set('projectName', 'Find Lost Item API');
    $map->set('description', 'This project is a API server for the Find Lost Item app.');
    $ourTeam = new DunnArray();

    $leader = new DunnMap();
    $leader->set('name', 'Lê Thế Dũng');
    $leader->set('role', 'leader');
    $leader->set('student_id', '22IT049');

    $uyeng = new DunnMap();
    $uyeng->set('name', 'Nguyễn Thị Tố Uyên');
    $uyeng->set('role', 'member');
    $uyeng->set('student_id', '22NS082');

    $vinh = new DunnMap();
    $vinh->set('name', 'Phạm Văn Ngọc Vinh');
    $vinh->set('role', 'member');
    $vinh->set('student_id', 'UNKNOWN');

    $xuan = new DunnMap();
    $xuan->set('name', 'Nguyễn Thị Thanh Xuân');
    $xuan->set('role', 'member');
    $xuan->set('student_id', 'UNKNOWN');

    $ourTeam->push($leader, $uyeng, $vinh, $xuan);

    $map->set('our_team', $ourTeam);

    $res->send($map);
  }
}
