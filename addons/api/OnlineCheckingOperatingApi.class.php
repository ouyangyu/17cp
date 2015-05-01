<?php
/**
 * 网上阅卷系统data操作接口
 * @author terry.fu
 * @date 2014-09-12
 *   
 *  接口返回值编号定义：
 * 0001：success
 * 0002：json  null error
 * 0003,0007:insert table error
 * 0005:database connecting error
 * 0004：database creating error
 * 0006:table creating error
 * 0008:school or program or subject null error
 * */
class OnlineCheckingOperatingApi extends Api {
	
	// 目标数据库
	private $goaldbname;
	/**
	 * 学生单科成绩分数API
	 * @return mixed|string  */
	public function getStudentScoreInfo() {
		// 获取pdo对象
		$pdo = $this->dbConn ('exams');
		if ($pdo != '0005') {
			try {
				$sql = '   select a.id,b.program_id ,a.school_name,a.grade,a.class,a.program_name,a.exam_name,a.student_id,
						   a.subject_id,a.totalscore,b.exam_date
						   from exams.exam_totalscore_info a join exams.exam_program_info b on 
						   a.school_name=b.school_name and 
						   a.program_name=b.program_name and a.subject_id=b.subject_id
						   group by a.program_name, a.student_id,a.subject_id
						   order by a.id';
				$info = $pdo->query ($sql);
				if ($info) {
					// 设置获取类型
					$recordsarray = $info->fetchAll(PDO::FETCH_ASSOC);
					$res=null;					
					foreach ($recordsarray as $k=>$val) {
						$res[$k]['schoolid']=$this->getSchoolId($val['school_name'], $pdo);
						$res[$k]['gradeid']=$res[$k]['schoolid'].$this->getgradeId($val['grade']);
						$res[$k]['classid']=$res[$k]['gradeid'].$this->getClassId($val['class']);
						$res[$k]['login']=$val['student_id'];
						$res[$k]['score']=$val['totalscore'];
						$res[$k]['paperid']=$this->getPaperId($pdo,$val['school_name'],$val['program_name'],$val['subject_id']);
						$res[$k]['subjectid']=$val['subject_id'];
						$res[$k]['time']=$val['exam_date'];
						$res[$k]['resultpaper']=='';//null
						$res[$k]['paperpackid']=$this->getPaperPackId($val['program_id'], $pdo);//null
						$res[$k]['packtype']=$this->getPackType($val['program_id'], $pdo);
						$res[$k]['resultdetailid']='';//null
					}
					exit(json_encode($res));
					/* //组装json	
					$tojson=json_encode( $recordsarray);
					//将unicode中文编码转换成utf-8
					//注：使用此方法，在只有转码后的json数组输出时，才能正常显示。				
					$json= $this->unicodechgToChn ($tojson); */
					//return $json;
				} else {
					return '0002';
				}
			} catch (Exception $e) {
				//dump ( $e->getMessage () . '::::::::::' . $pdo->errorInfo () );
				return '0003';
			}
		} else {
			return '0005';
		}
	}
	
	/**
	 * 试卷信息API
	 * @return mixed|string  */
	public function getExamPaperInfo()
	{
		// 获取pdo对象
		$pdo = $this->dbConn ('exams');
		if ($pdo!='0005') {
			try{
				$sql='  select program_id,program_name,exam_name,exam_date,exam_grade,
					    exam_class,exam_type,school_name,subject_id 
					    from exam_program_info group by program_id,program_name,school_name';
				$info=$pdo->query($sql);
				if ($info) {
					// 设置获取类型
					$recordsarray = $info->fetchAll(PDO::FETCH_ASSOC);
					$res=null;
					foreach ($recordsarray as $k=>$val) {
						$res[$k];
						$classArray=explode(',',$val['exam_class']);
						if (is_array($val)) {
							$schoolid=$this->getSchoolId($val['school_name'], $pdo);
							$gradeid=$schoolid.$this->getGradeId($val['exam_grade']);
							$paperid=$this->getPaperId($pdo,$val['school_name'],$val['program_name'],$val['subject_id']);
							$papertime=$val['exam_date'];
							$papername=$val['exam_name'];
							$paperscore='';
							$subjectid=$val['subject_id'];
							$paperpic='';
							$paperpackid=$this->getPaperPackId($val['program_id'], $pdo);
							if (!empty($val['exam_class'])) {
								foreach ($classArray as $ck=>$cval) {
									$res[$k][$ck]['schoolid']=$schoolid;
									$res[$k][$ck]['gradeid']=$gradeid;
									$res[$k][$ck]['classid']=$gradeid.$this->getClassId($classArray[$ck]);
									$res[$k][$ck]['paperid']=$paperid;									
									$res[$k][$ck]['papertime']=$papertime;
									$res[$k][$ck]['papername']=$papername;
									$res[$k][$ck]['paperscore']=$paperscore;
									$res[$k][$ck]['subjectid']=$subjectid;
									$res[$k][$ck]['paperpic']=$paperpic;
									$res[$k][$ck]['paperpackid']=$paperpackid/* $GLOBALS['paperpackidarray'][$val['program_id']] */;
								}
							}
						}
								
					}
					//dump($res);
					exit(json_encode($res));
					//组装json
					//$tojson=json_encode( $res);
					//将unicode中文编码转换成utf-8
					//注：使用此方法，在只有转码后的json数组输出时，才能正常显示。
					//$json= $this->unicodechgToChn ($tojson);
					//return $json;
				} else {
					return '0002';
				}
			}catch (Exception $e) {
				return '0003';
			}
		} else {
			return '0005';
		}
	} 
	
	/**
	 * 联考信息API
	 * @return mixed|string  */
	public function getPaperPack()
	{
		// 获取pdo对象
		$pdo = $this->dbConn ('exams');
		if ($pdo!='0005') {
			try {
				$sql='	select a.program_id ,a.exam_date, a.program_name,a.school_name,
						a.exam_grade,a.exam_class,a.exam_type
						from exams.exam_program_info a
						group by a.program_name,a.school_name';
				$info=$pdo->query($sql);
				$pid=array();
				if ($info) {
					// 设置获取类型
					$recordsarray = $info->fetchAll(PDO::FETCH_ASSOC);
					$result=null;
					//$GLOBALS['paperpackidarray']=null;
					foreach ($recordsarray as $k=>$val) {
						
						$result[$k];
						//$number=count($recordsarray);
						if (is_array($val)) {
							$schoolID=$this->getSchoolId($val['school_name'], $pdo);
							$greadID =$this->getGradeId($val['exam_grade']);
							//$paperPackID = $this->creatOnlyRandom();
							$paperpackid=$this->getPaperPackId($val['program_id'], $pdo);
							//记录paperpackid到数组中
							/* if (!array_key_exists($val['program_id'], $GLOBALS['paperpackidarray'])) {
								$GLOBALS['paperpackidarray'][$val['program_id']]=$paperPackID;
							} */							
							$paperPackName= $val['program_name'];
							$createTmie=$val['exam_date'];
							$sendStatue='1';
							$packType=$this->getPackType($val['program_id'],$pdo);
							if (!empty($val['exam_class'])) {
								$arrayClass=explode(',', $val['exam_class']);
								foreach ($arrayClass as $key=>$value){
									$result[$k][$key]['paperpackid']=$paperpackid;
									$result[$k][$key]['paperpackname']=$paperPackName;
									$result[$k][$key]['schoolid']=$schoolID;
									$result[$k][$key]['gradeid']=$schoolID.$greadID;
									$result[$k][$key]['classid']=$schoolID.$greadID.$this->getClassId($value);
									$result[$k][$key]['createtime']=$createTmie;
									$result[$k][$key]['sendstatues']=$sendStatue;
									//$result[$k][$key]['packtype']=$number==1?'1':'2';
									$result[$k][$key]['packtype']=$packType;
								}								
							}							
						}
					}
					exit(json_encode($result));					
					/*//组装json
					$tojson=json_encode($temparray);
					//将unicode中文编码转换成utf-8
					//注：使用此方法，在只有转码后的json数组输出时，才能正常显示。
					$json= $this->unicodechgToChn ($tojson);
					return $json;
					//return $tojson;
				} */
				}
		
			} catch (Exception $e) {
				return '0003';
			}
		} else {
			return '0005';
		}
	}
	
	/**
	 * 班级总平均分API
	 * @return mixed|string  */
	public function getClassTotalScoreAvg()
	{
		// 获取pdo对象
		$pdo = $this->dbConn ('exams');
		if ($pdo!='0005') {
			try{
				$sql='	select  c.program_id ,c.program_name,c.school_name,c.grade,c.class,avg(c.totalscore) totalscore
						from (select  a.student_id,a.exam_id,a.student_name,a.school_name,a.grade,
						a.class,a.subject_id,a.totalscore,a.program_name,
						(select distinct b.program_id  from  exams.exam_program_info b 
						where  b.program_name=a.program_name ) program_id
						from exams.exam_totalscore_info a
						group by program_id,a.student_id,a.subject_id) c
						group by c.program_id,c.class';
				$info=$pdo->query($sql);
				if ($info) {
					// 设置获取类型
					$recordsarray = $info->fetchAll(PDO::FETCH_ASSOC);
					$res=null;
					foreach ($recordsarray as $k=>$val) {						
						$res[$k]['schoolid']=$schoolid=$this->getSchoolId($val['school_name'], $pdo);
						$res[$k]['gradeid']=$gradeid=$schoolid.$this->getGradeId($val['grade']);
						$res[$k]['classid']=$classid=$gradeid.$this->getClassId($val['class']);
						$res[$k]['totalscore']=$val['totalscore'];
						$res[$k]['paperpackid']=$this->getPaperPackId($val['program_id'], $pdo);
					}
					exit(json_encode($res));					
					/* //组装json
					$tojson=json_encode( $recordsarray);
					//将unicode中文编码转换成utf-8
					//注：使用此方法，在只有转码后的json数组输出时，才能正常显示。
					$json= $this->unicodechgToChn ($tojson);
					return $json; */
					//return $tojson;
				}
			} catch (Exception $e) {
				return '0003';
			}
		} else {
			return '0005';
		}
	}
	
	/**
	 * 班级学科平均分API
	 * @return mixed|string  */
	public function getClassSubjectAvgScore()
	{
		// 获取pdo对象
		$pdo = $this->dbConn ('exams');
		if ($pdo!='0005') {
			try{
				$sql='	select  c.program_id ,c.program_name,c.subject_id,c.school_name,c.grade,
						c.class,avg(c.totalscore) avgscore,sum(c.totalscore) totalscore
						from (select a.student_id,a.exam_id,a.student_name,a.school_name,a.grade,
						a.class,a.subject_id,a.totalscore,a.program_name,
						(select distinct b.program_id  from  exams.exam_program_info b 
						where  b.program_name=a.program_name ) program_id
						from exams.exam_totalscore_info a group by program_id,a.student_id,a.subject_id) c
						group by c.program_id,c.class,c.subject_id';
				$info=$pdo->query($sql);
				if ($info) {
					// 设置获取类型
					$recordsarray = $info->fetchAll(PDO::FETCH_ASSOC);
					$res=null;
					foreach ($recordsarray as $k=>$val) {
						
						$res[$k]['schoolid']=$schoolid=$this->getSchoolId($val['school_name'], $pdo);
						$res[$k]['gradeid']=$gradeid=$schoolid.$this->getGradeId($val['grade']);
						$res[$k]['classid']=$gradeid.$this->getClassId($val['class']);
						$res[$k]['subjectid']=$val['subject_id'];
						$res[$k]['subavgscore']=$val['avgscore'];
						$res[$k]['totalscorevag']='';/* $val['totalscore'] */
						$res[$k]['paperid']=$this->getPaperId($pdo,$val['school_name'],$val['program_name'],$val['subject_id']);
						$res[$k]['paperpackid']=$this->getPaperPackId($val['program_id'], $pdo);
						
					}
					exit(json_encode($res));
					//组装json					
					/* $tojson=json_encode( $recordsarray);
					//将unicode中文编码转换成utf-8
					//注：使用此方法，在只有转码后的json数组输出时，才能正常显示。
					$json= $this->unicodechgToChn ($tojson);
					return $json; */
				} else {
					return '0002';
				}
			} catch (Exception $e) {
				return '0003';
			}
		} else {
			return '0005';
		}
	}
	
	/**
	 * 学生总成绩API
	 * @return mixed|string  */
	public function getStudentTotalScore()
	{
		// 获取pdo对象
		$pdo = $this->dbConn ('exams');
		if ($pdo!='0005') {
			try{
				 $sql='	 select  c.student_id ,c.exam_id,c.student_name,c.school_name ,c.grade,c.class,
						 sum(c.totalscore) totalscore,c.program_id ,c.program_name
						 from  (select  a.student_id,a.exam_id,a.student_name,a.school_name,a.grade,
						 a.class,a.subject_id,a.totalscore,a.program_name,
						 (select distinct b.program_id  from  exams.exam_program_info b 
						 where  b.program_name=a.program_name ) program_id
						 from exams.exam_totalscore_info a
						 group by program_id,a.student_id,a.subject_id) c
						 group by c.program_id,c.student_id'; 
				$info=$pdo->query($sql);			
				 if ($info) {
					// 设置获取类型
					$recordsarray = $info->fetchAll(PDO::FETCH_ASSOC);
					$res=null;
					foreach($recordsarray as $k=>$val) {
						$res[$k]['schoolid']=$schoolid=$this->getSchoolId($val['school_name'], $pdo);
						$res[$k]['gradeid']=$gradeid=$schoolid.$this->getGradeId($val['grade']);
						$res[$k]['classid']=$gradeid.$this->getClassId($val['class']);
						$res[$k]['totalscore']=$val['totalscore'];
						$res[$k]['login']=$val['student_id'];
						//$this->getPaperId($pdo,$val['school_name'],$val['program_name'],$val['subject_id']);						
						$res[$k]['paperpackid']=$this->getPaperPackId($val['program_id'], $pdo);
						$res[$k]['is_read']='0';
					}
					exit(json_encode($res));
					/* //组装json
					$tojson=json_encode( $recordsarray);
					//将unicode中文编码转换成utf-8
					//注：使用此方法，在只有转码后的json数组输出时，才能正常显示。
					$json= $this->unicodechgToChn ($tojson);
					return $json; */
				} else {
					return '0002';
				} 
			} catch (Exception $e) {
				return '0003';
			}
		} else {
			return '0005';
		}
	}
	
	/**
	 * 试题详情API
	 * @return mixed|string  */
	public function getTestQuestions()
	{
		// 获取pdo对象
		$pdo = $this->dbConn ('exams');
		if ($pdo!='0005') {
			try{
			/* 	$sql='	select  c.student_id ,c.exam_id,c.student_name,c.school_name ,c.grade,c.class,
						sum(c.totalscore) totalscore,c.program_id ,c.program_name
						from  (select  a.student_id,a.exam_id,a.student_name,a.school_name,a.grade,
						a.class,a.subject_id,a.totalscore,a.program_name,
						(select distinct b.program_id  from  exams.exam_program_info b
						where  b.program_name=a.program_name ) program_id
						from exams.exam_totalscore_info a
						group by program_id,a.student_id,a.subject_id) c
						group by c.program_id,c.student_id';
				$info=$pdo->query($sql); */
				
				$info=$pdo->query('CALL tranquestb()');
				
				if ($info) {
					$row=$info->fetch(PDO::FETCH_ASSOC);
					$tbnames=$row['res'];	
					exit( $this->assemData($tbnames, $pdo));
					
				} else {
					return '0002';
				}
					/*//组装json
					$tojson=json_encode( $recordsarray);
					//将unicode中文编码转换成utf-8
					//注：使用此方法，在只有转码后的json数组输出时，才能正常显示。
					$json= $this->unicodechgToChn ($tojson);
					return $json;
				} */
			} catch (Exception $e) {
				return '0003';
			}
		} else {
			return '0005';
		}
	}
	
	/**
	 * 同步学校信息API
	 */
	public function syncSchoolInfo() {
		// 数据库连接
		$dbcon = $this->dbConn ('exams');		
		if ($dbcon != '0005') {
			$sql = "	select school_id,school_name,province,city,area   
						from exams.exam_school_info group by school_name 
						order by school_id asc";			
			$schoolinfo = $dbcon->query ( $sql );
			$schoolinfo->setFetchMode ( PDO::FETCH_ASSOC );			
			$schoolarray = $schoolinfo->fetchAll ();			
			 // $dsnexam = 'mysql:host=192.168.1.53;port=3306;dbname=nanhaori' ; 
			 // 外网连接字符串 $username = 'root'; 
			 //try { $dbcongoal = new PDO ( $dsnexam, $username, 'yiwantiao' ); 
			 // 连接外网数据库 // $dbcon = new PDO ( $dsnexam, $username, 'sakai105' ); 
			 // 连接105数据库 $dbcongoal->query ( "SET NAMES utf8" ); 
			 // 作为调试pdo错误使用 $dbcongoal->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION ); } 
			 //catch ( Exception $e ) { dump($e->getMessage().'code'.$e->getCode()); return '0005-1'; }
			// 获取目标数据库连接配置
			$dbcongoal = $this->targetDb ();
			$xml_array = $this->getXML ();
			$dbname = $xml_array ['dbname'];
			foreach ( $schoolarray as $k => $val ) {
				$insertkey = '';
				foreach ( $val as $ck => $cval ) {
					$insertkey .= $ck . ',';
					// $insertval.='"'.$cval.'",';
				}				
				$area = $this->getAreaCode ( $val ['province'], $val ['city'], $val ['area'], $dbcon );
				if ($area == null) {
					$area = '0,0,0';
				}
				$partofinsertval = $val ['school_id'] . '","' . $val ['school_name'] . '",';							
				$cols=str_replace ( 'school_name', 'title', $this->substrString ( $insertkey ) ) ;
				$insertsql = 'insert into ' . $dbname . '.ts_schools(' .$cols . ') values( "' . $partofinsertval . $area . ')';				
				$num += $dbcongoal->exec ( $insertsql );
			}
			if ($num > 0) {
				return '0001';
			}
		}
	}
	
	/**
	 * 拼装试题表数据
	 * @param unknown $tbnames 数据表名称
	 * @param string $pdo pdo对象
	 * @return string 拼装完成的数据 */
	private function assemData($tbnames,$pdo='') 
	{
			$tbarray=explode(',', $tbnames);
			$res=null;
			foreach ($tbarray as $k=>$val) {
				 $colarray=	$this->getColname($val, $pdo);
				//重建数据库连接，以解决SQLSTATE[HY000]: 
				//General error: 2014 Cannot execute queries while other unbuffered queries are active.
				 $pdo=$this->dbConn('exams');				
				$keguanarray=$this->keguanRecord($colarray[0],$colarray[2],$val,$pdo);
				$pdo=$this->dbConn('exams');				
				$zhuguanarray=$this->zhuguanRecord($colarray[1],$colarray[2],$val,$pdo);
				$res[$k]['quenum']=$this->getPaperQuestionNum($colarray[0], $colarray[1]);
				$res[$k]['detail']=array_merge_recursive($keguanarray,$zhuguanarray); 
				//$res=array_merge_recursive($keguanarray,$zhuguanarray);
			}
			return json_encode($res);
	}
	
	/**
	 * 试卷试题数量
	 * @param unknown $keguancols 客观题列名数组
	 * @param unknown $zhuguancols 主观题列名数组
	 * @return number 试题数量  */
	private function getPaperQuestionNum($keguancols,$zhuguancols)
	{
		$num=0;
		$cols=array_merge($keguancols,$zhuguancols);
		foreach ($cols as $k=>$val) {
			//小题记入试题总数
			if (strstr($val, 'index')==true) {
				$num++;
			}
			//小题不计入试题总数
			/* if (strstr($val, 'keguanindex')==true||strstr($val, 'zhuguanqueindex')==true) {
				$num++;
			} */
		}
		//dump($num);
		return $num;
	}
	
	/**
	 * 处理主观题
	 * @param unknown $keguan 主观题表字段名称
	 * @param unknown $com 通用部分表字段名称
	 * @param unknown $tbname 数据库表名称
	 * @param unknown $pdo pdo对象
	 * @return number  主观题数组 */
	private function zhuguanRecord($zhuguan,$com,$tbname,$pdo)
	{
		$zhuguanlength=count($zhuguan);
		//拼装公共部分表字段为字符串
		$sqls='';
		foreach ($com as $key=>$val) {
			$sqls.=$val.',';
		}
		//拼接多结果集查询的sql语句
		$querystring='';
		for ($i=0;$i<$zhuguanlength;$i++) {
			if (strstr($zhuguan[$i],'smallquenum')==true) {
				$querystring.='select '.$sqls.$zhuguan[$i-2].' as "qindex",'.$zhuguan[$i-1].' as "qscore",'.$zhuguan[$i].' as "qchild" from '.$tbname.' union ';
			} elseif (strstr($zhuguan[$i],'smallquescore')==true) {
				$querystring.='select '.$sqls.$zhuguan[$i-1].' as "qindex",'.$zhuguan[$i].' as "qscore","qchild" from '.$tbname.' union ';
			}
		}
		//查询数据
		try {
		$querystring=substr($querystring, 0,count($querystring)-7);
		$zhuguanInfo=$pdo->query($querystring);
		} catch (PDOException $pe ) {
			//dump($pe->getMessage());
		}
		 if ($zhuguanInfo) {
			$infoarray=$zhuguanInfo->fetchAll(PDO::FETCH_ASSOC);
			$res=null;
			//拼装主观题数组数据
			 foreach ($infoarray as $k=>$val) {
				$res[$k]['schoolid']=$this->getSchoolId($val['school_name'],$pdo);
				$temp=$this->getTempInfo($val['school_name'], $val['program_name'],  $val['student_id'], $tbname);
				$res[$k]['gradeid']=$res[$k]['schoolid'].$this->getGradeId($temp['grade']);
				$res[$k]['class']=$res[$k]['gradeid'].$this->getClassId($temp['class']);
				$res[$k]['studentid']=$val['student_id'];
				$res[$k]['paperid']=$temp['paper_id'];
				$res[$k]['paperpackid']=$temp['paper_pack_id'];
				$res[$k]['qindex']=$val['qindex'];
				$res[$k]['qscore']=$val['qscore'];
				$res[$k]['qtype']='zhuguan';
				$res[$k]['qchild']=$val['qchild']=='qchild'?'0':$val['qchild'];
				$res[$k]['qvideoid']=1;
			} 
			return $res;
		}  
	}
	
	/**
	 * 处理客观题
	 * @param unknown $keguan 客观题表字段名称
	 * @param unknown $com 通用部分表字段名称
	 * @param unknown $tbname 数据库表名称
	 * @param unknown $pdo pdo对象
	 * @return number  客观题数组 */
	private function keguanRecord($keguan,$com,$tbname,$pdo)
	{
		$keguanlength=count($keguan);
		$sqls='';
		foreach ($com as $key=>$val) {
			$sqls.=$val.',';
		}
		//拼接多结果集查询的sql语句
		$querystring='';
		for ($i=0;$i<$keguanlength;$i++) {
			if ($i%2!=0) {
				$querystring.='select '.$sqls.$keguan[$i-1].' as "qindex",'.$keguan[$i].' as "qscore" from '.$tbname.' union ';
			}
		}
		try {
		$querystring=substr($querystring, 0,count($querystring)-7);
		$keguanInfo=$pdo->query($querystring);
		} catch (PDOException $pe ) {
			//dump($pe->getMessage());
		}
		if ($keguanInfo) {
			$infoarray=$keguanInfo->fetchAll(PDO::FETCH_ASSOC);
			$res=null;
			
			//拼装客观题数组数据
			   foreach ($infoarray as $k=>$val) {
				$res[$k]['schoolid']=$this->getSchoolId($val['school_name'],$pdo);
				$temp=$this->getTempInfo($val['school_name'], $val['program_name'],  $val['student_id'], $tbname);				
				$res[$k]['gradeid']=$res[$k]['schoolid'].$this->getGradeId($temp['grade']);
				$res[$k]['class']=$res[$k]['gradeid'].$this->getClassId($temp['class']);
				$res[$k]['studentid']=$val['student_id'];
				$res[$k]['paperid']=$temp['paper_id'];				
				$res[$k]['paperpackid']=$temp['paper_pack_id'];
				$res[$k]['qindex']=$val['qindex'];
				$res[$k]['qscore']=$val['qscore'];
				$res[$k]['qtype']='keguan';
				$res[$k]['qchild']='0';
				$res[$k]['qvideoid']=1;
			  } 		 
			  return $res;
		}
	}
	
	/**
	 * getTestQuestions接口需要的字段
	 * @param unknown $schoolname 学校名称
	 * @param unknown $programname 项目名称
	 * @param unknown $studentid 学生id
	 * @param unknown $tbname 数据库表名称
	 * @param string $subjectid 科目id
	 * @return mixed  查询结果 */
	private function getTempInfo($schoolname,$programname,$studentid,$tbname,$subjectid='')
	{
		$sql='select b.grade,b.class,c.paper_id,c.paper_pack_id from '.$tbname.' a
		join exam_totalscore_info b on b.program_name=a.program_name   and a.school_name=b.school_name and a.subject_type=b.subject_id
		join exam_program_info c on  c.program_name=a.program_name   and a.school_name=c.school_name and a.subject_type=c.subject_id
		where b.student_id="'.$studentid.'" and a.program_name= "'.$programname.' " and a.school_name="'.$schoolname.'" 
			group by b.grade,b.class';
		
		 try {
			$pdo=$this->dbConn('exams');
			$temp=$pdo->query($sql);
			if ($temp) {
				return $temp->fetch(PDO::FETCH_ASSOC);
			} else {
			}
		} catch(PDOException $pe) {
			//dump('function getTempInfo() excceotion :'.$pe->getMessage());
		}	 		
	}
	
	/**
	 * 数据库表字段名称
	 * @param unknown $tbname 数据库表
	 * @param unknown $pdo pdo对象
	 * @return multitype:Ambigous <NULL, unknown> NULL 表字段名称数组 */
	private function getColname($tbname,$pdo)
	{
		$sql='select COLUMN_NAME from information_schema.COLUMNS where table_name ="'.$tbname.'"';
		$col=mysql_query($sql);// $pdo->query($sql);
		$keguanarray=null;
		$zhuganarray=null;
		$commonarray=null;
		if ($col) {
				$keguan=0;
				$zhuguan=0;
				$common=0;
			 while ($colrow=mysql_fetch_array($col,MYSQL_ASSOC)) {
			 	switch ($colrow['COLUMN_NAME']) {
			 		case 'id' :
			 		case 'keguannum':
			 		case 'zhuguannum':
			 			continue;
			 		break;
			 		case strstr($colrow['COLUMN_NAME'], 'keguan')== true  :
			 			$keguanarray[$keguan]=$colrow['COLUMN_NAME'];
			 			$keguan++;
			 			break;			
			 		case strstr($colrow['COLUMN_NAME'], 'zhuguan')== true  :
			 		case strstr($colrow['COLUMN_NAME'], 'small')== true  :
			 			$zhuganarray[$zhuguan]=$colrow['COLUMN_NAME'];
			 			$zhuguan++;
			 			break;
			 		default :
			 			/* $keguanarray[$keguan]=$colrow['COLUMN_NAME'];
			 			$keguan++;
			 			$zhuganarray[$zhuguan]=$colrow['COLUMN_NAME'];
			 			$zhuguan++; */
			 			$commonarray[$common]=$colrow['COLUMN_NAME'];
			 			$common++;
			 			break;
			 	}
			}
		}
		//返回客观题，主观题，通用字段名称
		return array($keguanarray,$zhuganarray,$commonarray);
		
	}
	
	/**
	 * 查询对应学校编号
	 * @param unknown $schoolname 学校名称
	 * @param unknown $dbostatument pdo对象
	 * @return string|Ambigous <>  学校编号 */
	private function getSchoolId($schoolname,$dbostatument)
	{
		try{
			$sql='	select school_id from exams.exam_school_info where school_name="'.$schoolname .'" 
					group by school_name';
			$re=$dbostatument->query($sql);
			if($re){
				$re->setFetchMode ( PDO::FETCH_ASSOC );
				$re=$re->fetch();
			} else {
				return '';
			}
		} catch (PDOException $pe) {
			//dump($pe->getMessage());
		}
		return $re['school_id'];
	}
	
	/**
	 * 获得班级编号
	 * @param unknown $schoolname 学校名称
	 * @param unknown $gradename 年级名称
	 * @param unknown $pdo pdo对象
	 * @return string  年级编号 */
	private function getGradeId($gradename,$schoolname='',$pdo='') 
	{
		if (preg_match("/[\x7f-\xff]/", $gradename)) {
			$gradeid=$this->recognizeGrade($gradename);
		} else {
			/* if (count($gradename)==1) { */
			if ($gradename>10) { 
				$gradeid='0'.$gradename;
			} else {
				$gradeid='00'.$gradename;
			} 
		}		
		return $gradeid; 		
	}
	
	/**
	 * 获得班级编号
	 * @param unknown $schoolname 学校名称
	 * @param unknown $gradename 年级名称
	 * @param unknown $classname 班级名称
	 * @param unknown $pdostatument pdo对象
	 * @return string  班级编号 */
	private function getClassId($classname)
	{
		if (preg_match("/[\x7f-\xff]/", $classname)) {
			//如果只含有中文
			if (!preg_match("/\\d/", $classname)) {
				$classname= $this->chnToNum($classname);
			} 
			$classid=$this->findNum($classname);
			if ($classid>=10) {
				$classid='0'.$classid;
			} else {
				$classid='00'.$classid;
			}			
		} else {
			//没有中文
			if ($classname>=10) {
				$classid='0'.$classname;
			} else {
				$classid='00'.$classname;
			}
		}
		return $classid;
	}
	
	/**
	 * 获取试卷编号
	 * @param unknown $pdo pdo对象
	 * @param string $schoolname 学校名称
	 * @param string $programname 项目名称
	 * @param string $subjectid 科目id
	 * @return Ambigous <>  试卷编号 */
	private function getPaperId($pdo,$schoolname='',$programname='',$subjectid='')
	{
		$sql='	select paper_id from exam_program_info where school_name="'.$schoolname.'" 
				and program_name="'.$programname.'" and subject_id="'.$subjectid.'"';
		$res=$pdo->query($sql);
		if ($res) {
			$pid=$res->fetch(PDO::FETCH_ASSOC);
			return$pid['paper_id']; 
		}
		return '';
	}
	
	/**
	 * 获得联考编号
	 * @param unknown $programid 项目编号
	 * @param unknown $pdo pdo对象
	 * @return Ambigous <>|string  联考编号 */
	private function getPaperPackId($programid,$pdo)
	{
		$sql='select paper_pack_id from exam_program_info where program_id="'.$programid.'"';
		$res=$pdo->query($sql);
		if ($res) {
			$prid=$res->fetch(PDO::FETCH_ASSOC);
			return $prid['paper_pack_id'];
		} 
		return '' ;
	}	
	
	/**
	 * 将含有汉字的题号转换成数字
	 * @param unknown $str 需要转换的字符串
	 * @return mixed  转换后的字符串 */
	private function chnToNum($str){
		$standard_array=array(
				'五十'=>'50','四十九'=>'49','四十八'=>'48','四十七'=>'47','四十六'=>'46','四十五'=>'45',
				'四十四'=>'44','四十三'=>'43','四十二'=>'42','四十一'=>'41','四十'=>'40','三十九'=>'39',
				'三十八'=>'38','三十七'=>'37','三十六'=>'36','三十五'=>'35','三十四'=>'34','三十三'=>'33',
				'三十二'=>'32','三十一'=>'31','三十'=>'30','二十九'=>'29','二十八'=>'28','二十七'=>'27',
				'二十六'=>'26','二十五'=>'25','二十四'=>'24','二十三'=>'23','二十二'=>'22','二十一'=>'21',
				'二十'=>'20','十九'=>'19','十八'=>'18','十七'=>'17','十六'=>'16','十五'=>'15',
				'十四'=>'14','十三'=>'13','十二'=>'12','十一'=>'11','十'=>'10',
				'九'=>'9','八'=>'8','七'=>'7','六'=>'6','五'=>'5',
				'四'=>'4','三'=>'3','二'=>'2','一'=>'1');
		//替换其中的汉字
		 while(preg_match("/[\x7f-\xff]/", $str)) {
			foreach ($standard_array as $k=>$val) {
				if (strstr ( $str,  $k ) == true) {
					$str=str_replace($k, $val, $str);
					$str=str_replace('班', '', $str);
				}
			}
		} 
		return $str;
	}
	
	/**
	 * 找到字符串中的数字
	 * @param string $str 混有数字的字符串
	 * @return string  字符串中的数字 */
	private	function findNum($str='')
	{
		$str=trim($str);
		if(empty($str)){return '';}
		$result='';
		for ($i=0;$i<strlen($str);$i++) {
			if(is_numeric($str[$i])){
				$result.=$str[$i];
			}
		}
		return $result;
	}
	
	/**
	 * 判断考试类型
	 * @param unknown $str 项目id
	 * @return number  考试类型 */
	private function getPackType($programid,$pdo)
	{	
		$sql="	select count(*) from 
				( select * from exam_program_info 
				where program_id = '".$programid."' group by school_name) c";
		$res=$pdo->query($sql);
		$schoolnum=$res->fetch(PDO::FETCH_NUM);
		if ($schoolnum[0]>1) {
			return 2;
		}
			return 1;
	}
	
	/**
	 * 识别汉字，并转换成对应的数字
	 * @param unknown $gradename 需要识别的年级名称
	 * @return string  识别后的年级编号 */
	private function recognizeGrade($gradename) 
	{
		switch ($gradename) {
			case strstr($gradename,'七')==true:
				$gradename='007';
				break;
			case strstr($gradename,'八')==true:
				$gradename='008';
				break;
			case strstr($gradename,'九')==true:
				$gradename='009';
				break;
			case strstr($gradename,'高一')==true:
				$gradename='010';
				break;
			case strstr($gradename,'高二')==true:
				$gradename='011';
				break;
			case strstr($gradename,'高三')==true:
				$gradename='012';
				break;
			default :
				$gradename='0';
		}
		return $gradename;
	}
	
	/**
	 * 生成联考编号
	 * @param unknown $id 项目编号
	 * @return Ambigous <string> 项目编号与联考编号相匹配的数组  */
	private function createPaperPackId($id)
	{
		$pid=array();
		if (!array_key_exists($id, $pid)) {
			//如果不存在该项目id,为它生成packid	
			$pid[$id]=$this->creatOnlyRandom();
		} 
		return $pid[$id];
	}
	
	/**
	 * 生成不重复随机数
	 * @return string  */
	private function creatOnlyRandom()
	{		
		return uniqid(true);		
	}
	
	/**
	 * 获得学校所在地的统一编码
	 * 
	 * @param unknown $province 省编号        	
	 * @param unknown $city     市编号   	
	 * @param unknown $area     区编号   	
	 * @param unknown $dbcon    pdo对象    	
	 * @return Ambigous <void, mixed> 
	 */
	private function getAreaCode($province, $city, $area, $dbcon) {
		$sql = '	select c.area_id province   ,b.area_id city ,a.area_id area from nhtest.ts_area a
			 		join nhtest.ts_area b on a.pid=b.area_id join nhtest.ts_area c on b.pid=c.area_id
					 where a.title= "' . $area . '" and 
					 b.title= "' . $city . '" and c.title= "' . $province . '"';
		$areacode = $dbcon->query ( $sql );
		$areacode->setFetchMode ( PDO::FETCH_ASSOC );
		$areaarray = $areacode->fetch ();
		foreach ( $areaarray as $k => $val ) {
			$res .= '"' . $val . '" ,';
		}
		return $this->substrString ( $res );
	}
	
	/**
	 * 去除字符串最后一个字符
	 * 
	 * @param unknown $string        	
	 * @return string
	 */
	private function substrString($string) {
		return substr ( $string, 0, strlen ( $string ) - 1 );
	}
	
	/**
	 * 将unicode编码的中文转换成utf-8
	 * 
	 * @param unknown $unicodejson        	
	 * @return mixed
	 */
	private function unicodechgToChn($unicodejson) {
		// 解决编码后，中文转换成unicode编码格式问题。效果：输出征程的中文格式。
		$c="iconv('UCS-2BE', 'UTF-8', pack('H4', '\\1'))";
		$str = preg_replace ( "#\\\u([0-9a-f]+)#ie",$c , $unicodejson );
		return $str;
	}
	
	/**
	 * 目标数据库
	 * 
	 * @return string PDO
	 */
	private function targetDb() {
		$xml_array = $this->getXML ();
		$host = $xml_array ['host'];
		$port = $xml_array ['port'];
		$dbname = $xml_array ['dbname'];
		$username = $xml_array ['user'];
		$pwd = $xml_array ['pwd'];
		// 目标数据库属性赋值
		$this->goaldbname = $xml_array ['dbname'];
		$dsnexam = 'mysql:host=' . $host . ';port=' . $port . ';dbname=' . $dbname; // 外网连接字符串
		
		try {
			$dbcongoal = new PDO ( $dsnexam, $username, $pwd ); // 连接外网数据库
			// $dbcon = new PDO ( $dsnexam, $username, 'sakai105' ); // 连接105数据库
			$dbcongoal->query ( "SET NAMES utf8" );
			// 作为调试pdo错误使用
			// $dbcongoal->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
		} catch ( Exception $e ) {
			// dump($e->getMessage().'code'.$e->getCode());
			return '0005-1';
		}
		return $dbcongoal;
	}
	
	/**
	 * 连接数据库，并返回连接对象
	 *
	 * @param unknown $dbname        	
	 * @return string PDO
	 */
	private function dbConn($dbname = '') {
		// $dsnexam = 'mysql:host=192.168.1.105;dbname=' . $dbname; // 105连接字符串
		$dsnexam = 'mysql:host=127.0.0.1;dbname=' . $dbname; // 外网连接字符串
		$username = 'root';
		try {
			$dbcon = new PDO ( $dsnexam, $username, 'sakai123' ); // 连接外网数据库
			// $dbcon = new PDO ( $dsnexam, $username, 'sakai105' ); // 连接105数据库
			$dbcon->query ( "SET NAMES utf8" );
			// 作为调试pdo错误使用
			 $dbcon->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
			 $dbcon->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY,true);
		} catch ( Exception $e ) {
			return '0005';
		}
		return $dbcon;
	}
	
	/**
	 * 从配置文件中获取PDO参数
	 * 
	 * @return array
	 */
	public function getXML() {
		$xml_array = ( array ) simplexml_load_file ( 'config/dbconfig.xml' );
		return $xml_array;
	}
}