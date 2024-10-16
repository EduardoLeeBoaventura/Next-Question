### Select
```php
   <?php
    # crud.php
    $response = $specific_crud->execSelect("people");
    var_dump($response);

    echo "<br><br>";

    $response = $specific_crud->execSelect("people", null, null, null, 'id', '>');
    var_dump($response);
    
    echo "<br><br>";
   
    $tables = [
      " people P " => [],
      " type_has_people THP " => [["THP.id_person", "P.id"]],
      " type_people TP " => [["TP.id", "THP.id_type"]]
    ];

    $columns = [
      " COUNT(*) `repeat` ",
      " P.job ",
      " TP.name name_type "
    ];

    $conditions = [
      ['P.id', 20],
      " P.job LIKE '%doctor%' "
    ];

    $group_by = [
      " P.job ",
      " THP.id_type "
    ];

    $response = $specific_crud->execSelect($tables, $columns, $conditions, $group_by, '`repeat`', '>', 0, 100);
    var_dump($response);
```