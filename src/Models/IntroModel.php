<?php

namespace DUVX\Models;

/**
 * @author LT.Dung
 */
class IntroModel implements \JsonSerializable
{
  function jsonSerialize(): mixed
  {
    // This function will be called when you use json_encode() function with this object like:
    // $introModel = new IntroModel();
    // echo json_encode($introModel);
    // The result will be:
    return [
      'teamName' => 'DUVX',
      'projectName' => 'Find Lost Item API',
      'description' => 'This project is a API server for the Find Lost Item app.',
      'our_team' => [
        [
          'name' => 'Lê Thế Dũng',
          'role' => 'leader',
          'studentId' => '22IT049'
        ],
        [
          'name' => 'Nguyễn Thị Tố Uyên',
          'role' => 'member',
          'studentId' => '22NS082'
        ],
        [
          'name' => 'Phạm Văn Ngọc Vinh',
          'role' => 'member',
          'studentId' => 'UNKNOWN'
        ],
        [
          'name' => 'Nguyễn Thị Thanh Xuân',
          'role' => 'member',
          'studentId' => 'UNKNOWN'
        ],
      ]
    ];
  }
}
