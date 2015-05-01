<?php
/**
 * 
 * @author terry.fu
 * date:2014-07-29
 * instruction：网上阅卷系统API
 * OnlineChecking
 * 
 * 接口返回值编号定义：
 * 0001：success
 * 0002：json  null error
 * 0003,0007:insert table error
 * 0005:database connecting error
 * 0004：database creating error
 * 0006:table creating error
 * 0008:school or program or subject null error
 *  */
class OnlineCheckingApi extends Api {
	 private $databasename; // 需要连接的数据库名称
	
	/**
	 * 获取阅卷系统中的学校信息API
	 * 
	 * @return string  */
	public function postSchoolInfo() {
		
		if (! empty ( $_REQUEST ['json'] )) // 使用post方式传递过来的json
			$schoolinfo = $_REQUEST ['json'];
		else if (! empty ( $GLOBALS ['HTTP_RAW_POST_DATA'] )) // 使用curl方式传递过来的json
			$schoolinfo = $GLOBALS ['HTTP_RAW_POST_DATA'];
		$tbcolumns = $_REQUEST ['tbcolumns'] == null ? '' : $_REQUEST ['tbcolumns'];
		$database=$this->getDbNameFromXML();
		//$database = 'exams';
		$tablename = 'exam_school_info';
		
		$info = json_decode ( $schoolinfo, true );
		
		if ($info != null) {

			// 创建数据库
			$isCreated = $this->dbCreate ( $database );
			if ($isCreated == true) {
				// 连接数据库
				
				$DbCon = $this->dbConn();
				if ($DbCon != '0005') {

					if ($tbcolumns != '') {
						// 判断数据表是否存在；如果不存在，创建一个新表
						$istbcreate = $this->createTable ( $DbCon, $tablename, $tbcolumns );
						if ($istbcreate == true) {
							foreach ($info as $key=>$value){
								//1:判断数据是否重复
								//2:插入新数据
							}
							$insnum = $this->query ( $DbCon, $info, $tablename, 'school' ); // 执行插入
							if ($insnum != '0007' && $insnum != null) {
								return '0001';
							} else {
								return '0003';
							}
						} else {
							return '0006';
						}
					} else {
						
						$insnum = $this->query ( $DbCon, $info, $tablename, 'school',null ); // 执行插入
						if ($insnum != '0007' && $insnum != null) {
							return '0001';
						} else {
							return '0003';
						}
					}
				} else {
					return '0005';
				}
			} else {
				return '0004';
			}
		} else {
			return '0002';
		}
	}
	
	/**
	 * 阅卷系统中的考试项目信息API
	 *
	 * @return string
	 */
	public function postProgramInfo() {
		if (! empty ( $_REQUEST ['json'] )) // 使用post方式传递过来的json
			$programinfo = $_REQUEST ['json'];
		else if (! empty ( $GLOBALS ['HTTP_RAW_POST_DATA'] )) // 使用curl方式传递过来的json
			$programinfo = $GLOBALS ['HTTP_RAW_POST_DATA'];
		$tbcolumns = $_REQUEST ['tbcolumns'] == null ? '' : $_REQUEST ['tbcolumns'];
		$database=$this->getDbNameFromXML();
		//$database = 'exams';
		$tablename = 'exam_program_info';
		$info = json_decode ( $programinfo, true );
		if ($info != null) {
			
			// 创建数据库
			$isCreated = $this->dbCreate ( $database );
			if ($isCreated == true) {
				// 连接数据库
				$DbCon = $this->dbConn ();
				if ($DbCon != '0005') {
					if ($tbcolumns != '') {
						// 判断数据表是否存在；如果不存在，创建一个新表
						$istbcreate = $this->createTable ( $DbCon, $tablename, $tbcolumns );
						if ($istbcreate == true) {
							$insnum = $this->query ( $DbCon, $info, $tablename, 'program' ); // 执行插入

							if ($insnum != '0007' && $insnum != null) {
								return '0001';
							} else {
								return '0003';
							}
						} else {
							return '0006';
						}
					} else {
						$insnum = $this->query ( $DbCon, $info, $tablename, 'program',null ); // 执行插入

						if ($insnum != '0007' && $insnum != null) {
							return '0001';
						} else {
							return '0003';
						}
					}
				} else {
					return '0005';
				}
			} else {
				return '0004';
			}
		} else {
			return '0002';
		}
	}
	
	/**
	 * 阅卷系统中的试卷总分信息API
	 *
	 * @return string
	 */
	public function postTotalscoreInfo() {
		if (! empty ( $_REQUEST ['json'] )) // 使用post方式传递过来的json;适用于一般的请求，java请求
			$totalscoreinfo = $_REQUEST ['json'];
		else if (! empty ( $GLOBALS ['HTTP_RAW_POST_DATA'] )) // 使用curl方式传递过来的json;适用于php使用curl方式请求
			$totalscoreinfo = $GLOBALS ['HTTP_RAW_POST_DATA'];

		$tbcolumns = $_REQUEST ['tbcolumns'] == null ? '' : $_REQUEST ['tbcolumns'];
		$database=$this->getDbNameFromXML();
		//$database = 'exams';
		$tablename = 'exam_totalscore_info';
		
		$info = json_decode ( $totalscoreinfo, true );

		if ($info != null) {
			
			// 创建数据库
			$isCreated = $this->dbCreate ( $database );
			if ($isCreated == true) {
				// 连接数据库
				$DbCon = $this->dbConn ();
				if ($DbCon != '0005') {
					if ($tbcolumns != '') {
						// 判断数据表是否存在；如果不存在，创建一个新表
						$istbcreate = $this->createTable ( $DbCon, $tablename, $tbcolumns );
						if ($istbcreate == true) {
							$insnum = $this->query ( $DbCon, $info, $tablename, 'totalscore' ); // 执行插入

							if ($insnum != '0007' && $insnum != null) {
								return '0001';
							} else {
								return '0003';
							}
						} else {
							return '0006';
						}
					} else {
						$insnum = $this->query ( $DbCon, $info, $tablename, 'totalscore',null ); // 执行插入

						if ($insnum != '0007' && $insnum != null) {
							return '0001';
						} else {
							return '0003';
						}
					}
				} else {
					return '0005';
				}
			} else {
				return '0004';
			}
		} else {
			return '0002';
		}
	}
	
	/**
	 * 阅卷系统中的科目信息API,并将中文unicode编码格式转换成utf-8格式，正常显示。
	 *
	 * @return string
	 */
	public function getSubjectInfo() {
		try {
			$nanhaori = $this->dbConn ( 'nhtest' );
			$subject = $nanhaori->query ( "select subject_type,subject_type_desc from nhtest.ts_subject_master" );
			$subjectarray = $subject->fetchAll ( PDO::FETCH_ASSOC );
		} catch ( ErrorException $e ) {
			return '0005';
		}
		$unicodejson = json_encode ( $subjectarray );
		//解决编码后，中文转换成unicode编码格式问题。效果：输出征程的中文格式。
		$str = preg_replace ( "#\\\u([0-9a-f]+)#ie", "iconv('UCS-2BE', 'UTF-8', pack('H4', '\\1'))", $unicodejson );
		return $str;
	}
	/**
	 * 阅卷系统中的小题分API,并将中文unicode编码格式转换成utf-8格式，正常显示。
	 *
	 * @return string
	 */
	public function postScoreInfo() {
		if (! empty ( $_REQUEST ['json'] )) // 使用post方式传递过来的json
			$scoreinfo = $_REQUEST ['json'];
		else if (! empty($GLOBALS['HTTP_RAW_POST_DATA'])) // 使用curl方式传递过来的json
			$scoreinfo = $GLOBALS ['HTTP_RAW_POST_DATA'];
		$tbcolumns = $_REQUEST ['tbcolumns'] == null ? '' : $_REQUEST ['tbcolumns'];
		$database=$this->getDbNameFromXML();
		//$database = 'exams';
		$tablename = 'exam_score_'; // 小题分前缀
		$info = json_decode ( $scoreinfo, true );
		if ($info != null) {
			// 创建数据库
			$isCreated = $this->dbCreate ( $database );
			if ($isCreated == true) {
				// 连接数据库
				$DbCon = $this->dbConn ();
				if ($DbCon != '0005') {
				
					$subject_id = $info [0] ['subject_type'];
					/* chg by terry.fu V2.9 
					//$school_name = $info [0] ['school_name'];
					//$program_name = $info [0] ['program_name'];
					//$querystatement='select s.school_id, p.program_id from ' .$database.'.exam_program_info p join ' .$database.'.exam_school_info s on p.school_name=s.school_name  where p.school_name="' . $school_name . '" and p.program_name="' . $program_name . '"';
					//$school_program_id = $DbCon->query ($querystatement);
					//$row = $school_program_id->fetch(); // 将查询结果全部取出，并放置到一个数组里边
					//$schoolid = $row ['school_id']; // 获取第一个查询结果
					//$programid = $row['program_id']; // 获取第二个查询结果
				 	$tablename = $tablename . $schoolid . '_' . $programid . '_' . $subject_id;
					// 判断数据表是否存在；如果不存在，创建一个新表
					if (empty ( $schoolid ) || empty ( $programid ) || empty ( $subject_id )) {
						return '0008'; 
						chg the end
						*/
					$tablename=date('Y_m_d').'_'.$tablename.'_'.$subject_id;
					if (empty($subject_id)) {
						return '0008';
					} else {
						$tbcol = $this->getTableColumns ( $info ); // 获取目标表的字段,需要提供字段类型
						$insertkey=$this->getTableColumns ( $info ,false);//获取目标表的字段，不需要提供字段类型，做插入使用。
						$istbcreate = $this->createTable ( $DbCon, $tablename, 'id int(10) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY ,' . substr ( $tbcol, 0, strlen ( $tbcol ) - 1 ) );
						if ($istbcreate == true) {
							$insnum = $this->query ( $DbCon, $info, $tablename, 'score' ,$insertkey);
							if ($insnum != '0007' && $insnum != null) {
								return '0001';
							} else {
								return '0003';
							}
						} else {
							return '0006';
						}
					}
				} else {
					return '0005';
				}
			} else {
				return '0004';
			}
		} else {
			return '0002';
		}
	}
	/**
	 * 获取将要创建表格的字段
	 * @param unknown $json 需要创建表格的json串
	 * @param string $isjudgetype 是否需要字段类型 
	 * @return string  */
	private function getTableColumns($json,$isjudgetype=true) {
		$n = 0;
		while ( list ( $key, $value ) = each ( $json ) ) {
			// json数组的最外层的
			if (is_array ( $value )) {
				while ( list ( $childkey, $childvalue ) = each ( $value ) ) {
					
					if (is_array ( $childvalue )) {
						
						while ( list ( $grandchildley, $grandchildvalue ) = each ( $childvalue ) ) {
							
							if (is_array ( $grandchildvalue )) {
								
								while ( list ( $great_grandchildley, $great_grandchildvalue ) = each ( $grandchildvalue ) ) {
									
									if (is_array ( $great_grandchildvalue )) {
									} else {
										if (strstr ( $great_grandchildley, 'keguanindex' ) == true) {
											$keguantiindex = $great_grandchildvalue;
										}
										if (strstr ( $great_grandchildley, 'zhuguanqueindex' ) == true) {
											//对13-16等类似试题块的处理
											/* if(strstr ( $great_grandchildvalue, '-' ) == true){
												$zhuguantiindex =str_replace('-', '_', $great_grandchildvalue) ;
											}else{
												$zhuguantiindex = $great_grandchildvalue;
											} */
											//对含有汉字（罗马，后续支持）特殊字符的题号做处理，将其转变为数字
											$zhuguantiindex=$this->changeToNumber($great_grandchildvalue);
										}									
										if (strstr ( $great_grandchildley, 'smallque' ) == true) {
											$zhuguantismallindex = $great_grandchildvalue;
										}
										if (strstr ( $great_grandchildley, 'keguan' ) == true){
											if($isjudgetype)
												$tbcolumnstring .= $great_grandchildley . '_' . $keguantiindex . $this->judgeFieldTypebyKey ( $great_grandchildley );
											else 
												$tbcolumnstring .= $great_grandchildley . '_' . $keguantiindex .',' ;
										}	
										else if (strstr ( $great_grandchildley, 'zhuguan' ) == true){
											if($isjudgetype)
												
												$tbcolumnstring .= $great_grandchildley . '_' . $zhuguantiindex . $this->judgeFieldTypebyKey ( $great_grandchildley );
											else
												$tbcolumnstring .= $great_grandchildley . '_' . $zhuguantiindex .',' ;
										}
										if($great_grandchildley == "smallquenum" ){
											if($isjudgetype) {
												$tbcolumnstring .= $great_grandchildley . '_' . $zhuguantiindex .$this->judgeFieldTypebyKey($great_grandchildley) ;
											}else {
												$tbcolumnstring .= $great_grandchildley . '_' . $zhuguantiindex .',' ;
											}
										}
										if ($great_grandchildley == "smallquenum" && $great_grandchildvalue > 0) {
											if($isjudgetype)
												for($i = 1; $i <= $great_grandchildvalue; $i ++) {
													$tbcolumnstring .= 'smallqueindex' . '_' . $zhuguantiindex . '_' . $i . $this->judgeFieldTypebyKey ( 'smallqueindex' );
													$tbcolumnstring .= 'smallquescore' . '_' . $zhuguantiindex . '_' . $i . $this->judgeFieldTypebyKey ( 'smallquescore' );
												}
											else 
												for($i = 1; $i <= $great_grandchildvalue; $i ++) {
													$tbcolumnstring .= 'smallqueindex' . '_' . $zhuguantiindex . '_' . $i.',' ;
													$tbcolumnstring .= 'smallquescore' . '_' . $zhuguantiindex . '_' . $i.',' ;
												}												
										}
									}
								}
							} else {
								// 客观题分数
								if($isjudgetype)
									$tbcolumnstring .= $grandchildley . $this->judgeFieldTypebyKey ( $grandchildley );
								else 
									$tbcolumnstring .= $grandchildley.',' ;
							}
						}
					} else {
						if($isjudgetype)
							$tbcolumnstring .= $childkey . $this->judgeFieldTypebyKey ( $childkey );
						else 
							$tbcolumnstring .= $childkey.',' ;
					}
					$n ++;
				}
			}
			if ($n >= 1)
				break;
		}
		return $tbcolumnstring;
	}
	
	/**
	 * 通过json串中数组的值，判断字段数据类型
	 *
	 * @param unknown $str        	
	 * @return string
	 */
	private function judgeFieldTypebyValue($str) {
		$filetype = '';
		if (! empty ( $str ))
			switch ($str) {
				case is_string ( $str ) :
					$filetype = 'varchar(150) default null ,';
					break;				
				case is_float ( $str ) :
					$filetype = 'float(5,1) default null ,';
					break;
				case 0 :
					$filetype = 'int(3) default null ,';
					break;
				case is_numeric ( $str ) :
					$filetype = 'int(3) default null ,';
					break;
				default :
					$filetype = 'varchar(100) default null ,';
					break;
			}
		return $filetype;
	}
	
	/**
	 * 将含有特殊字符的题号转换成数字
	 * @param unknown $str
	 * @return Ambigous <mixed, unknown>  */
	private function changeToNumber($str){

		//判断是否含有汉字
		if (preg_match("/[\x7f-\xff]/", $str)) {
			//echo "有中文";
			$str= $this->chnToNum($str);
			
		}else{
			//echo "没有中文";
		}
		//判断是否含有 '-'
		if(strstr ( $str, '-' ) == true) {
			 $str= str_replace('-', '_', $str) ;
		}
		return $str;
	}
	
	/**
	 * 将含有汉字的题号转换成数字
	 * @param unknown $str
	 * @return mixed  */
	private function chnToNum($str){
		$standard_array=array(
				'二十'=>'20','十九'=>'19','十八'=>'18','十七'=>'17','十六'=>'16','十五'=>'15',
				'十四'=>'14','十三'=>'13','十二'=>'12','十一'=>'11','十'=>'10',
				'九'=>'9','八'=>'8','七'=>'7','六'=>'6','五'=>'5',
				'四'=>'4','三'=>'3','二'=>'2','一'=>'1');
		//替换其中的汉字
		while(preg_match("/[\x7f-\xff]/", $str)) {
			foreach ($standard_array as $k=>$val) {
				if (strstr ( $str,  $k ) == true) {
					$str=str_replace($k, $val, $str);
				}
			}
		}
		return $str;
	}
	
	/**
	 * 通过json串中数组的键，判断字段数据类型
	 *
	 * @param unknown $str        	
	 * @return string
	 */
	private function judgeFieldTypebyKey($str) {
		$filetype = '';
		if (! empty ( $str ))
			switch ($str) {
				case strstr ( $str, "name" ) == true :
					$filetype = ' varchar(150) default null ,';
					break;				
				case strstr ( $str, "score" ) == true :
					$filetype = ' float(5,1) default null ,';
					break;
				case strstr ( $str, "index" ) == true :
					$filetype = ' varchar(50) default null ,';
					break;
				case strstr ( $str, "num" ) == true :
					$filetype = ' int(3) default null ,';
					break;
				case strstr ( $str, "id" ) == true :
					$filetype = ' varchar(50) default null ,';
					break;
				case strstr ( $str, "type" ) == true :
					$filetype = ' varchar(50) default null ,';
					break;
				default :
					$filetype = ' varchar(100) default null ,';
					break;
			}
		return $filetype;
	}
	
	/**
	 * 创建目标表
	 *
	 * @param unknown $dbcon        	
	 * @param unknown $tablename        	
	 * @param unknown $columns        	
	 * @return string boolean
	 */
	private function createTable($dbcon, $tablename, $columns) {
		try {	
			$dbcon->exec ( " create table if not exists " . $tablename . '(' . $columns . ')' );
		} catch ( Exception $e ) {
			//dump ( $dbcon->errorInfo () . '::::::::' . $e->getMessage () );
			return '0006';
		}
		return true;
	}
	
	/**
	 * 向目标表中插入数据
	 * @param unknown $dbcon
	 * @param unknown $json
	 * @param unknown $tablename
	 * @param unknown $flag 来自哪个接口 
	 * @param string $tbcol 表字段
	 * @return string  */
	private function query($dbcon, $json, $tablename, $flag,$tbcol=null) {
		while ( list ( $key, $value ) = each ( $json ) ) {
			$insertkey=$insertvalue=$programname=$schoolname = '';
			while ( list ( $childley, $childvalue ) = each ( $value ) ) {
				
				if (is_array ( $childvalue )) {
					
					while ( list ( $grandchildley, $grandchildvalue ) = each ( $childvalue ) ) {
						
						if (is_array ( $grandchildvalue )) {
														
							while ( list ( $great_grandchildley, $great_grandchildvalue ) = each ( $grandchildvalue ) ) {
								
								if (is_array ( $great_grandchildvalue )) {
									
									while ( list ( $grandgreat_grandchildley, $grandgreat_grandchildvalue ) = each ( $great_grandchildvalue ) ) {
										
										if (is_array ( $grandgreat_grandchildvalue )) {
											
											while ( list ( $grandgrandgreat_grandchildley, $grandgrandgreat_grandchildvalue ) = each ( $grandgreat_grandchildvalue ) ) {
												
												$insertkey.=$grandgrandgreat_grandchildley.',';
												$insertvalue .= '"' . $grandgrandgreat_grandchildvalue . '"' . ',';
											}
										} else {
											$insertkey.=$grandgreat_grandchildley.',';
											$insertvalue .= '"' . $grandgreat_grandchildvalue . '"' . ',';
										}
									}
								} else {									
									$insertvalue .= '"' . $great_grandchildvalue . '"' . ',';
									$insertkey.=$great_grandchildley.',';
									if ($great_grandchildley == "zhuguansmallquenum" && $great_grandchildvalue > 0) {
									}
								}
							}
						} else {
							$insertkey.=$grandchildley.',';
							$insertvalue .= '"' . $grandchildvalue . '"' . ',';
						}
					}
				} else {
					// 学校名称等
					$insertkey.=$childley.',';
					$insertvalue .= '"' . $childvalue . '"' . ',';
					if ($childley == "program_name") {
						$programname=$childvalue;
					}else if($childley=='school_name'){
						$schoolname=$childvalue;
					}
				}
			}
			try {
				/* 需要自定义表字段的时候可以使用到 */
				// $insertnum += $dbcon->exec ( 'insert into ' . $tablename . ' values ('.$school_id.',' . substr ( $insertvalue, 0, strlen ( $insertvalue ) - 1 ) . ')' );
				switch ($flag) {
					case 'school':
						$sidquery = $dbcon->query ( 'select max(school_id) from '.$this->databasename.'.' . $tablename ); // 查询最大的id
						$sidquery->setFetchMode ( PDO::FETCH_NUM ); // 获取单行当列查询结果
						$schoolid = $sidquery->fetchColumn ( 0 );
						// 如果数据表为空则赋初值
						if ($schoolid == null) {
							//chg by terry.fu V2.9
							//$schoolid = 1000001;
							$schoolid='0101001';
							//chg the end 
												
						} else {
							//chg terry.fu V2.9
							//$schoolid++;							
							$schoolid='0'.strval(1+$schoolid);
							//chg the end 
						}																		
						$insertnum += $dbcon->exec ( 'insert into '.$this->databasename.'.'.$tablename .' ('. $insertkey.'school_id) values ('.$insertvalue .'"'.$schoolid. '")' );
						break;
					case 'program' :
						//chg by terry.fu V2.9						
						//$sql1='SELECT a.program_id  from '.$this->databasename.'.' . $tablename . ' a where a.program_name="'.$programname.'" and a.school_name="'.$schoolname.'"' ;
						//chg the end 
						//查询项目是否存在
						$sql1='SELECT a.program_id,a.paper_pack_id  from '.$this->databasename.'.' . $tablename . ' a where a.program_name="'.$programname.'"' ;// .'" and a.school_name="'.$schoolname.'"'
						$isexistprogram=$dbcon->query($sql1);
						/* $isexistprogram->setFetchMode ( PDO::FETCH_NUM ); // 获取单行当列查询结果
						$programid = intval($isexistprogram->fetchColumn ( 0 ));//获得programid */
						$isexistprogram->setFetchMode ( PDO::FETCH_ASSOC ); // 获取单行当列查询结果
						$programid = $isexistprogram->fetch();//获得programid
						//如果存在这个这个项目
						if($programid!=null){
							$sql2='SELECT  max(paper_id) paper_id from '.$this->databasename.'.' . $tablename /* . ' a where a.program_id="'.$programid.'"' */;//查询到该表中的最大paper_id						
							$maxpidquery = $dbcon->query ($sql2); // 查询最大的paper_id
							$maxpidquery->setFetchMode ( PDO::FETCH_NUM ); // 获取单行当列查询结果
							$paperid = intval($maxpidquery->fetchColumn ( 0 ));//获得max-paperid							
							$paperid++;
							$paperpackid=$programid['paper_pack_id'];//联考编号
						}else{
							//$sql3='SELECT a.program_id , max(a.paper_id) paper_id from '.$this->databasename.'.' . $tablename . ' a where a.program_id=(select max(program_id) from '.$this->databasename.'.' . $tablename . ')';							
							$sql3='select max(b.program_id) program_id ,(select max(a.paper_id) from '.$this->databasename.'.exam_program_info a) paper_id from '.$this->databasename.'.exam_program_info b ';
							$pidquery = $dbcon->query ($sql3); // 查询最大的项目id,paper_id
							$row = $pidquery->fetch(); // 将查询结果全部取出，并放置到一个数组里边
							$programid = intval($row ['program_id']); // 获取第一个查询结果
							$paperid   = intval($row ['paper_id']); // 获取第二个查询结果
							$paperpackid=uniqid(true);
							if ($programid == null) $programid = 1000000001;// 如果数据表为空则赋初值								
							else $programid++;
							
							if($paperid==null) $paperid = 10000000001;														
							else  $paperid++;
						}	
						$insertnum += $dbcon->exec ( 'insert into '.$this->databasename.'.' . $tablename.' ( program_id,'.$insertkey . 'paper_id,paper_pack_id) values (' . $programid . ',' . $insertvalue . $paperid.',"'.$paperpackid . '")' );
						break;
					case 'totalscore' :
						$insertnum += $dbcon->exec ( 'insert into '.$this->databasename.'.' . $tablename .' ('.substr ( $insertkey, 0, strlen ( $insertkey ) - 1 ). ') values (' .substr ( $insertvalue, 0, strlen ( $insertvalue ) - 1 ) . ')' );
						break;
					case 'score' :						
						if($tbcol!=null)
							$insertkey=$tbcol;
						$insertnum += $dbcon->exec ( 'insert into '.$this->databasename.'.' . $tablename .' ('.substr ( $insertkey, 0, strlen ( $insertkey ) - 1 ). ') values (' .substr ( $insertvalue, 0, strlen ( $insertvalue ) - 1 ) . ')' );
						break;
					default :
						break;
				}

			} catch ( Exception $e ) {
				 dump ( $dbcon->errorInfo () . '::::::::' . $e->getMessage () );
				return '0007';
				// 可在此创建事务，出现错误，可回滚
			}
		}
		return $insertnum;
	}
	
	/**
	 * 创建数据库，并创建数据库中的表结构
	 *
	 * @param unknown $dbname        	
	 * @param string $sql        	
	 * @return string
	 */
	private function dbCreate($dbname, $sql = '') {
		try {
			$result = M ()->query ( "create database if not exists " . $dbname ); // 创建数据库
		} catch ( Exception $e ) {
			return '0004';
		}
		return true;
	}

	/**
	 * 连接数据库，并返回连接对象
	 *
	 * @param unknown $dbname        	
	 * @return string PDO
	 */
	private function dbConn(/*$dbname*/) {
		//通过xml 获得接口数据库连接配置
		$xml_array=$this->getXML();
		$host=$xml_array['host'];
		$port=$xml_array['port'];
		$dbname=$xml_array['dbname'];
		$user=$xml_array['user'];
		$pwd=$xml_array['pwd'];
		$this->databasename=$xml_array['dbname'];

		// xml config string 
		$dsnexam = 'mysql:host='.$host.'; port='.$port.'; dbname=' . $dbname;

		// 105server string
		// $dsnexam = 'mysql:host=192.168.1.105;dbname=' . $dbname;
		// qingcloud string
		//$dsnexam = 'mysql:host=127.0.0.1;dbname=' . $dbname; 
		try {
			
			// qingcloud server string
			//$dbcon = new PDO ( $dsnexam, 'root', 'sakai123' ); 
			// 105server string
			 // $dbcon = new PDO ( $dsnexam, $username, 'sakai105' ); 
			//xml config string
			 $dbcon=new PDO($dsnexam, $user, $pwd);
			 
			$dbcon->query ( "SET NAMES utf8" );
			
			// 作为调试pdo错误使用
			$dbcon->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
		} catch ( Exception $e ) {
			return '0005';
		}
		return $dbcon;
	}
	
	/**
	 * 从xml文件中获得需要操作的数据库
	 * @return Ambigous <>  */
	private function getDbNameFromXML(){
		$xml_array=$this->getXML();
		$this->databasename=$xml_array['dbname'];
		return $xml_array['dbname'];
	}
	
	/**
	 * 获取config下的dbconfig.xml文件中的数据库连接配置项
	 * @return array  */
	private function getXML(){
		$xml_array=(array)simplexml_load_file('config/dbconfig.xml');
		return $xml_array;
	}
}