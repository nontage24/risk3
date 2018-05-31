<?php 
header('Content-type: text/json; charset=utf-8');
function __autoload($class_name) {
    include '../class/'.$class_name.'.php';
}
set_time_limit(0);
$conn_DB= new EnDeCode();
$read="../connection/conn_DB.txt";
$conn_DB->para_read($read);
$conn_db=$conn_DB->Read_Text();
$conn_DB->conn_PDO();
$rslt = array();
$series = array();
    include_once '../template/plugins/function_date.php';
    include_once '../template/plugins/funcDateThai.php';
if (empty($_GET['data'])) {
     if($date >= $bdate and $date <= $edate){
                             $y= $Yy;
                             $Y= date("Y");
                             $year = $Yy;
                             $years = $year + 543;
                            }else{
                            $y = date("Y");
                            $Y = date("Y") - 1;
                            $year = date('Y');
                            $years = $year + 543;
                            }
                        } else {
                            $YeaR=$_GET['data'];
                            $y = $_GET['data'] - 543;
                            $Y = $y - 1;
                            $year = $_GET['data'] - 543;
                            $years = $year + 543;
                        }
                        $date_start = "$Y-10-01";
                        $date_end = "$y-09-30";
                            $sql_sum = "select count(t1.takerisk_id) as sum from takerisk t1
                                                    Where recycle='N' and subcategory!='' and t1.move_status='N'
                                                    and t1.take_date between '$date_start' and '$date_end'";
                            $conn_DB->imp_sql($sql_sum);
                            $sum_rmT=$conn_DB->select_a();
                            $sql_D = "select count(t1.takerisk_id) as sumD from takerisk t1
                                                    left outer join mngrisk m1 on t1.takerisk_id=m1.takerisk_id
                                                    where t1.recycle='N' and (m1.mng_status='N' or ISNULL(m1.mng_status)) and t1.subcategory!='' and t1.move_status='N'
                                                    and t1.take_date between '$date_start' and '$date_end'";
                            $conn_DB->imp_sql($sql_D);
                            $sum_rmD = $conn_DB->select_a();
                            $sql_D2 = "select count(t1.takerisk_id) as sumD2 from takerisk t1
                                                    inner join mngrisk m1 on m1.takerisk_id=t1.takerisk_id and m1.chk_show=1
                                                    where m1.adminchk_id='' and t1.recycle='N' and m1.mng_status='Y' and t1.subcategory!='' and t1.move_status='N'
                                                    and t1.take_date between '$date_start' and '$date_end'";
                            $conn_DB->imp_sql($sql_D2);
                            $sum_rmD2 = $conn_DB->select_a();
                            
                            $sql_results = "select rs_id from results order by rs_id";
                            $conn_DB->imp_sql($sql_results);
                            $results = $conn_DB->select();
                            foreach ($results as $key => $value) {
                                $sql = "select count(t1.takerisk_id) as sum from takerisk t1
                                        inner join mngrisk m1 on t1.takerisk_id=m1.takerisk_id and m1.chk_show=1
                                        where adminchk_id=".$value['rs_id']." and t1.recycle='N' 
                                        and t1.take_date between '$date_start' and '$date_end' and mng_status='Y'";
                            $conn_DB->imp_sql($sql);
                            $sum_rm = $conn_DB->select_a();
                            $series["s".$key] = (int) $sum_rm['sum'];
                            }
                            $series['total'] = (int) $sum_rmT['sum'];
                            $series['review'] = (int) $sum_rmD['sumD'];
                            $series['assessment'] = (int) $sum_rmD2['sumD2'];
                            $series['fyear'] = $years;
                            $series['date_start'] = DateThai1($date_start);
                            $series['date_end'] = DateThai1($date_end);
                            print json_encode($series);
$conn_DB->close_PDO();
                            ?>