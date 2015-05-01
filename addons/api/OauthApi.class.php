<?php
class OauthApi extends Api{
	//��ȡRequestKey
	public function request_key(){
		
		//dump(md5('13451672388'));
		//dump(md5('123456789'));
		return array($this->getRequestKey());
	}

	
	//��ȡ8λRequestKey
	private function getRequestKey(){
		
		return "THINKSNS";	//��Ҫ�޸�
	}

	//��֤����
	public function authorize(){
		
		
		
		if(!function_exists('mcrypt_module_open')){
			$message['message'] = '����������:ȱ�ټ�����չmcrypt';
			$message['code']    = '00000';
			exit( json_encode( $message ) );
		}

		$_REQUEST = array_merge($_GET,$_POST);
		if(!empty($_REQUEST['uid']) && !empty($_REQUEST['passwd'])){


			$message['requid'] = $_REQUEST['uid'];
			$message['reqpd'] = $_REQUEST['passwd'];
            if($_REQUEST['platform']=='ios'){
                $username = desdecrypt(str_replace('*','+',t($_REQUEST['uid'])), $this->getRequestKey());
                $password = desdecrypt(str_replace('*','+',t($_REQUEST['passwd'])), $this->getRequestKey());
            }else{
                $username = desdecrypt(t($_REQUEST['uid']), $this->getRequestKey());
                $password = desdecrypt(t($_REQUEST['passwd']), $this->getRequestKey());
            }

			$message['login'] = $username;
			$message['password']    = $password;
			//exit(json_encode( $message ));
	    	if($this->isValidEmail($username)){
	    		$map['email'] = $username;
	    		
	    	}else{
	    		$map['login'] = $username;
	    		
	    	}
	    	//$map['profile_id'] = '2';
	    	//����ʺŻ�ȡ�û���Ϣ
	    	$user = model('User')->where($map)->field('uid,email,password,login_salt,is_audit,is_active')->find();

			if($user && (md5($password.$user['login_salt']) == $user['password'])){
				if($user['is_audit']!=1 || $user['is_active']!=1){
					$message['message'] = '����ʺ���δ�����δͨ�����';
	        		$message['code']    = '00002';
	        		exit( json_encode( $message ) );
				}
				//��¼token
				if( $login = D('')->table(C('DB_PREFIX').'login')->where("uid=".$user['uid']." AND type='location'")->find() ){
					$data['oauth_token']         = $login['oauth_token'];
					$data['oauth_token_secret']  = $login['oauth_token_secret'];
					$data['uid']                 = $user['uid'];
				}else{
					$data['oauth_token']         = getOAuthToken($user['uid']);
					$data['oauth_token_secret']  = getOAuthTokenSecret();
					$data['uid']                 = $user['uid'];
					$savedata['type']            = 'location';
					$savedata = array_merge($savedata,$data);
					D('')->table(C('DB_PREFIX').'login')->add($savedata);
				}
				return $data;
			}else{
				$this->verifyError();
			}
    	}else{
    		$this->verifyError();
    	}
	}

	//ע���ʺţ�ˢ��token
	public function logout(){
		$_REQUEST = array_merge($_GET,$_POST);
		if(!empty($_REQUEST['uid'])){
			//�ʺš�����ͨ�����
			$username = desdecrypt(t($_REQUEST['uid']), $this->getRequestKey());	
		}
		//�ж��ʺ�����
    	if($this->isValidEmail($username)){
    		$map['email'] = $username;
    	}else{
    		$map['login'] = $username;
    	}
		//�ж������Ƿ���ȷ
		$user = model('User')->where($map)->field('uid')->find();
		if($user){
			$data['oauth_token']         = getOAuthToken($user['uid']);
			$data['oauth_token_secret']  = getOAuthTokenSecret();
			$data['uid']                 = $user['uid'];
			D('')->table(C('DB_PREFIX').'login')->where("uid=".$user['uid']." AND type='location'")->save($data);
			return 1;
		}else{
			return 0;
		}
	}

	//��֤�ַ��Ƿ���email
	public function isValidEmail($email) {
		return preg_match("/[_a-zA-Z\d\-\.]+@[_a-zA-Z\d\-]+(\.[_a-zA-Z\d\-]+)+$/i", $email) !== 0;
	}

	public function register(){
		$return = array();
		$regmodel = model('Register');
		$regdata = model('Xdata')->get('admin_Config:register');
		
		//�ǳơ����롢���䡢�Ա������ȷ���ش�����Ϣ
		$uname = t( $this->data['uname'] );
		$sex = intval( $this->data['sex'] );
		$password = $this->data['password'];
		$email = t( $this->data['email'] );
		
		//����������֤
		if(!$regmodel->isValidEmail($email)) {
			$msg = $regmodel->getLastError();
			$return = array('status'=>0, 'msg'=>$msg);
			return $return;
		}
		if(!$regmodel->isValidName($uname)) {
			$msg = $regmodel->getLastError();
			$return = array('status'=>0, 'msg'=>$msg);
			return $return;
		}
		if (!$regmodel->isValidPassword($password, $password)) {
			$msg = $regmodel->getLastError();
			$return = array('status'=>0, 'msg'=>$msg);
			return $return;
		}
		
		$login_salt = rand(11111, 99999);
		
		//�����Ҫ�����ʾ��������ʹ��
		//�����Ҫ��ˣ����û���ʾ��˺���ܵ�¼
		
		$map['uname'] = $uname;
		$map['sex'] = $sex;
		$map['login_salt'] = $login_salt;
		$map['password'] = md5(md5($password).$login_salt);
		$map['login'] = $map['email'] = $email;
		$map['ctime'] = time();
		// ���״̬�� 0-��Ҫ��ˣ�1-ͨ�����
		$map['is_audit'] = $regdata['register_audit'] ? 0 : 1;
		$map['is_active'] = $regdata['need_active'] ? 0 : 1;
		$map['first_letter'] = getFirstLetter($uname);
		//�������Ľ����ķ����ƴ��
		if ( preg_match('/[\x7f-\xff]+/', $map['uname'] ) ){
			//�ǳƺ��س�ƴ�����浽�����ֶ�
			$map['search_key'] = $map['uname'].' '.model('PinYin')->Pinyin( $map['uname'] );
		} else {
			$map['search_key'] = $map['uname'];
		}
		$uid = model('User')->add($map);
		if ( $uid ){
			//�����¼���д��
			if(isset($this->data['type'])){
				$other['oauth_token']         = addslashes($this->data['access_token']);
				$other['oauth_token_secret']  = addslashes($this->data['refresh_token']);
				$other['type']                = addslashes($this->data['type']);
				$other['type_uid']            = addslashes($this->data['type_uid']);
				$other['uid']                 = $uid;
				M('login')->add($other);
			}
			$return = array('status'=>1, 'msg'=>'ע��ɹ�');
			return $return;
		} else {
			$return = array('status'=>0, 'msg'=>'ע��ʧ��');
			return $return;
		}
	}

	public function setDeviceToken(){
		$token = t ( $this->data['token'] );
		
		$uid = D('mobile_token')->where('uid='.intval($_REQUEST['uid'])." and token='".$token."'")->getField('uid');
		$data['mtime'] = time();
		$data['token'] = $token;
		$data['device_type'] = t ( $this->data['device_type'] );
		if ( $uid ){
			$data['uid'] = $uid;
			$res = D('mobile_token')->add($data);
		} else {
			$res = D('mobile_token')->where('uid='.$uid)->save($data);
		}
		return $res ? 1 : 0;
	}

	public function getOtherLoginInfo(){
		$type = addslashes($this->data['type']);
		$type_uid = addslashes($this->data['type_uid']);
		$access_token = addslashes($this->data['access_token']);
		$refresh_token = addslashes($this->data['refresh_token']);
		$expire = intval($this->data['expire_in']);
		if(!empty($type) && !empty($type_uid)){
			$user = M('login')->where("type_uid='{$type_uid}' AND type='{$type}'")->find();
			if($user && $user['uid']>0){
				if( $login = M('login')->where("uid=".$user['uid']." AND type='location'")->find() ){
					$data['oauth_token']         = $login['oauth_token'];
					$data['oauth_token_secret']  = $login['oauth_token_secret'];
					$data['uid']                 = $login['uid'];
				}else{
					$data['oauth_token']         = getOAuthToken($user['uid']);
					$data['oauth_token_secret']  = getOAuthTokenSecret();
					$data['uid']                 = $user['uid'];
					$savedata['type']            = 'location';
					$savedata = array_merge($savedata,$data);
					$result = M('login')->add($savedata);
					if(!$result)
						return -3;
				}
				return $data; 
			}else{
				return 0;
			}
		}else{
			return 0;
		}
	}
}