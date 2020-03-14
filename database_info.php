<?php


$title = " <tr>

        <td width=\"180\">字段名 </td>

        <td width=\"160\">字段类型</td>

        <td width=\"80\">可否为空</td>
     
        <td width=\"80\">Key</td> 

        <td width=\"60\">默认值</td>

        <td>字段描述</td>

    </tr>";

$indexTitle = " <tr>

        <td width=\"60\">COLUMN_NAME</td>
        
        <td width=\"60\">NON_UNIQUE</td>

        <td width=\"60\">INDEX_NAME</td>

        <td width=\"60\">SEQ_IN_INDEX</td>
     
        <td width=\"60\">COLLATION</td> 

        <td width=\"60\">CARDINALITY</td>
        
        <td width=\"60\">SUB_PART</td> 
        
        <td width=\"60\">PACKED</td> 
        
        <td width=\"60\">NULLABLE</td>
        
        <td width=\"60\">INDEX_TYPE</td>
        

    </tr>";


const HOST = "118.126.97.71";
const DBNAME = "daohang";
const USER = "root";
const PASSWORD = "";



$dns = "mysql:host="+HOST+";dbname=" + DBNAME;

$pdo = new PDO($dns, USER, PASSWORD);
$pdo->query('set names utf8');
$pdo->query('use information_schema');


$allTablesSql = 'SELECT table_name name,TABLE_COMMENT value FROM INFORMATION_SCHEMA.TABLES WHERE table_type=\'base table\' 
and table_schema = \'daohang\' order by table_name asc';
$query = $pdo->query($allTablesSql);

$rs = $query->fetchAll();


$allTablesRecordSql = 'select table_name,table_rows from tables where TABLE_SCHEMA = \'daohang\' order by table_rows desc; ';
$query2 = $pdo->query($allTablesRecordSql);
$rs2 = $query2->fetchAll();


//获取表名和记录数的对应关系
$recordArr = [];

foreach ($rs2 as $tableInfo) {

    $tableName = $tableInfo["table_name"];
    $tableRows = $tableInfo["table_rows"];
    $recordArr[$tableName] = $tableRows;
}


//获取表名和索引的对应关系
$allTablesIndexSql = ' SELECT * FROM INFORMATION_SCHEMA.STATISTICS WHERE TABLE_SCHEMA = \'daohang\'; ';
$query3 = $pdo->query($allTablesIndexSql);
$rs3 = $query3->fetchAll();



//获取表名和索引的对应关系,三维数组,键名是表名,键值是个二维数组,里面是这张表所有的索引
$indexArr = [];

foreach ($rs as $one) {
    $tName = $one["name"];
    $indexArr[$tName] = [];
}


foreach ($rs3 as $item) {
    $tNameWithIndex = $item["TABLE_NAME"];
    array_push($indexArr[$tNameWithIndex], $item);

}



$pdo->query('use daohang;');


$html = '';
foreach ($rs as $v) {
    $sql = "show full columns from `$v[name]` ";

    $query = $pdo->query($sql);
    $tableData = $query->fetchAll();
    $count = count($tableData);
    if (empty($v['value'])) {
        $v['value'] = '无';
    }


    $recordNum = $recordArr[$v['name']];


    $head = "<table class=\"tablelist\" style=\"margin:20px 0 0 20px; width:1200px;\">
    <tr><td colspan=\"10\" style=\"font-weight: bold; background:#abcbbb; \"> 表：[ $v[name]  ] &nbsp &nbsp &nbsp &nbsp &nbsp 描述: &nbsp <font color='blue'>$v[value] </font> &nbsp &nbsp &nbsp &nbsp &nbsp 行数: &nbsp <font color='purple'>$recordNum </font> </td></tr>";


    $indexHead = "<table class=\"tablelist\" style=\"margin:20px 0 0 20px; width: 1200px;\">
    <tr><td height=\"3px\" colspan=\"10\" style=\"font-weight: bold; background:salmon; \">   <font color='purple' size='1px'>&nbsp;<a href='https://blog.csdn.net/itas109/article/details/82879397' target='_blank'>索引信息:</a>  </font> </td></tr>";

    $fenge = "<table class=\"tablelist\" style=\"margin:20px 0 0 20px; width: 1200px;\">
    <tr><td height=\"3px\" colspan=\"10\" style=\"font-weight: bold; background:scroll; \">   <font color='#1e90ff' size='1px'>&nbsp;<HR SIZE=1> </font> </td></tr>"; //html画实线https://blog.csdn.net/lance_lot1/article/details/7921441


    $str = '';
    foreach ($tableData as $key => $item) {
        $field = $item['Field'];
        $type = $item['Type'];
        $is_null = $item['Null'];

        $key = empty($item['Key']) ? "&nbsp;- " : "<font color='#6a5acd'>" . $item['Key'] . "</font>";
        $default = empty($item['Default']) ? '无' : "<font color='#daa520'>" . $item['Default'] . "</font>";
        $comment = empty($item['Comment']) ? '无' : "<font color='red'>" . $item['Comment'] . "</font>";

        $body = "<tr>
        <td width=\"180\">$field</td>
        <td width=\"160\">$type</td>
        <td width=\"80\">$is_null</td>
        <td width=\"80\">$key</td>
        <td width=\"60\">$default</td>
        <td>$comment</td>
    </tr>";
        $str .= $body; //字段信息(如类型,可否为空,备注等)
    }


    $tableIndex = $indexArr[$v["name"]]; //单张表的所有索引信息,二维数组

    $indexStr = "";
    foreach ($tableIndex as $itemIndex) {
        $body = "<tr>
        <td width=\"60\">$itemIndex[COLUMN_NAME]</td>
        <td width=\"60\">$itemIndex[NON_UNIQUE]</td>
        <td width=\"60\">$itemIndex[INDEX_NAME]</td>
        <td width=\"60\">$itemIndex[SEQ_IN_INDEX]</td>
        <td width=\"60\">$itemIndex[COLLATION]</td>
        <td width=\"60\">$itemIndex[CARDINALITY]</td>
        <td width=\"60\">$itemIndex[SUB_PART]</td>
        <td width=\"60\">$itemIndex[PACKED]</td>
        <td width=\"60\">$itemIndex[NULLABLE]</td>
        <td width=\"60\">$itemIndex[INDEX_TYPE]</td>
    </tr>";

        $indexStr .= $body;

    }

    $html .= $head . $title . $str . $indexHead . $indexTitle . $indexStr . $fenge;

}

echo $html;
