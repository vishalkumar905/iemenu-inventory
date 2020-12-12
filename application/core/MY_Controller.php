<?php
	class Backend_Controller extends CI_Controller {
		public $pageTitle = 'Admin';
		public $navTitle = 'Admin';
		public $template = 'backend/backend.template.php'; 
		public $isLoggedIn;
		public $loggedInUserData;
		public $referrerUrl = '';
		public $loggedInUserId = '';
		public $loggedInUserName = '';
		public $loggedInUserImage = '';

		function __construct() {
			parent::__construct();

			$this->isLoggedIn = $this->session->userdata('isLoggedIn');
			
			if (empty($this->isLoggedIn)) {
				redirect(base_url());
			}

			$this->loggedInUserData = $this->session->userdata('loggedInUserData');
			$this->loggedInUserId = $this->session->userdata('loggedInUserData')['userId'];

			if (isset($this->loggedInUserData['name']))
			{
				$this->loggedInUserName = $this->loggedInUserData['name'];
			}

			if (isset($this->loggedInUserData['image']))
			{
				$this->loggedInUserImage = $this->loggedInUserData['image'];
			}
			else
			{
				$this->loggedInUserImage = base_url('assets/img/avatar.png');
			}

			$this->load->library('user_agent');
			$this->referrerUrl = $this->agent->referrer();
		}

		public function isUserAdmin()
		{
			if (!empty($loggedInUserData))
			{
				if ($loggedInUserData['type'] == ADMIN)
				{
					return true;
				}
			}

			return false;
		}

		public function isUserSuperAdmin()
		{
			if (!empty($loggedInUserData))
			{
				if ($loggedInUserData['type'] == SUPER_ADMIN)
				{
					return true;
				}
			}

			return false;
		}

		public function isUser()
		{
			if (!empty($loggedInUserData))
			{
				if ($loggedInUserData['type'] == USER)
				{
					return true;
				}
			}

			return false;
		}

		public function isUserVendor()
		{
			if (!empty($loggedInUserData))
			{
				if ($loggedInUserData['type'] == USER_VENDOR)
				{
					return true;
				}
			}

			return false;
		}

		public function doUpload($image, $uploadPath, $allowedTypes = null, $thumbnailData = null) 
		{
			$allowedTypes = $allowedTypes == null || $allowedTypes == '' ? 'gif|jpg|png|jpeg' : $allowedTypes;
			$uploadPath = './' . $uploadPath;

			if (!is_dir($uploadPath)) {
				mkdir($uploadPath, 0777, true);
			}

			$config['upload_path'] = $uploadPath;
			$config['allowed_types'] = $allowedTypes;
			$config['encrypt_name'] = TRUE;
			// $config['max_size'] = 2048;
			$this->load->library('upload', $config);
			$this->upload->initialize($config);

			if (!$this->upload->do_upload($image)) {
				$uploadData['err'] = 1;
				$uploadData['errorMessage'] = $this->upload->display_errors('<p class="text-danger">','</p>'); 
			} else {
				$file_data = $this->upload->data();
				$uploadData['err'] = 0;
				$uploadData['fileName'] = $file_data['file_name']; 

				if (!empty($thumbnailData) && $thumbnailData['create_thumbnail'] == TRUE) {
					if (!is_dir($thumbnailData['upload_path'])) {
						mkdir($thumbnailData['upload_path'], 0777, true);
					}
	
					$ratio = null;
					if (isset($thumbnailData['image_ratio'])) {
						$ratio = $thumbnailData['image_ratio'];	
					}

					$new_image = $thumbnailData['upload_path'];
					$this->createThumbnail($file_data['file_name'], $uploadPath, $new_image, $ratio);
				}
			}

			return $uploadData;
		}

		public function createThumbnail($file_name, $source_image, $new_image, $ratio = null) {
		
			$width = isset($ratio['width']) ? $ratio['width'] : 200;
			$height = isset($ratio['height']) ? $ratio['height'] : 200;

			$this->load->library('image_lib');
			$config['image_library'] = 'gd2'; 
			$config['source_image'] = $source_image.'/'.$file_name;
			$config['new_image'] = $new_image.'/'.$file_name;
			$config['maintain_ratio'] = TRUE; 
			$config['width'] = $width;
			$config['height'] = $height;

			$this->load->library('image_lib', $config);

			$this->image_lib->initialize($config);

			if (!$this->image_lib->resize()) {
				// echo $this->image_lib->display_errors();
			}
		}

		public function multiple_upload($image, $uploadPath) {
		
			$count = count($_FILES[$image]['name']);
			$FILES = $_FILES;
			$uploadData = [];

			if (isset($_FILES[$image])) {
				for ($i=0; $i < $count; $i++) { 

					$config['upload_path'] = $uploadPath;
					$config['allowed_types'] = 'gif|jpg|png|jpeg';
					$config['encrypt_name'] = TRUE;
					// $config['max_size'] = 2048;
					$this->load->library('upload');
					$this->upload->initialize($config);

					$_FILES[$image]['name'] = isset($FILES[$image]['name'][$i]['image']) ? $FILES[$image]['name'][$i]['image'] : '';
					$_FILES[$image]['type'] = isset($FILES[$image]['type'][$i]['image']) ? $FILES[$image]['type'][$i]['image'] : '';
					$_FILES[$image]['tmp_name'] = isset($FILES[$image]['tmp_name'][$i]['image']) ? $FILES[$image]['tmp_name'][$i]['image'] : '';
					$_FILES[$image]['error'] = isset($FILES[$image]['error'][$i]['image']) ? $FILES[$image]['error'][$i]['image'] : '';
					$_FILES[$image]['size'] = isset($FILES[$image]['size'][$i]['image']) ? $FILES[$image]['size'][$i]['image'] : '';

					if (!$this->upload->do_upload('choice')) {
						$uploadData[$i]['err'] = 1;
						$uploadData[$i]['errorMessage'] = $this->upload->display_errors('<p class="text-danger">','</p>'); 
					} else {
						$uploadData[$i]['err'] = 0;
						$uploadData[$i]['file_name'] = $this->upload->data()['file_name']; 
					}
				}
			}

			return $uploadData;
		}

		public function productTypes()
		{
			return [
				'' => 'Choose Product Type',
				PRODUCT_TYPE_SEMIPROCESSED => 'Semi Processed',
				PRODUCT_TYPE_RAWMATERIAL => 'Raw Materials',
			];
		}

		public function requestTransferTypes($requestTypeId = null)
		{
			$requestTypes = [
				'' => 'Choose Type',
				DIRECT_TRANSER_REQUEST => 'Direct Transfer',
				REPLENISHMENT_REQUEST => 'Replenishment Request',
			];

			return intval($requestTypeId) > 0 ? (isset($requestTypes[$requestTypeId]) ? $requestTypes[$requestTypeId] : '') : $requestTypes;
		}
		
		public function getProductTypeName($typeId = null)
		{
			$productTypes = $this->productTypes();
			return isset($productTypes[$typeId]) ? $productTypes[$typeId] : '';
		}

		public function getRequestStatus(int $statusId): string
		{
			switch ($statusId)
			{
				case STATUS_PENDING:
					return 'Pending';
				case STATUS_ACCEPTED:
					return 'Accepted';
				case STATUS_DISPATCHED:
					return 'Dispatched';
				case STATUS_REJECTED:
					return 'Rejected';
				default:
					return '';
			}
		}

		public function getAllRequestStatus(): array
		{
			return [STATUS_PENDING, STATUS_RECEIVED, STATUS_ACCEPTED, STATUS_DISPATCHED, STATUS_REJECTED];
		}

		public function changeArrayIndexByColumnValue($data, $columnName): array
		{
			$results = [];
			if (!empty($data) && !empty($columnName) && isset($data[0][$columnName]))
			{
				foreach($data as $row)
				{
					$results[$row[$columnName]] = $row;
				}
			}

			return $results;
		} 

		public function saveDataToCache($key, $value)
		{
			$this->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file'));
			$this->cache->save($key, $value, 300);
		}

		public function getDataFromCache($key)
		{
			$this->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file'));
			return $this->cache->get($key);
		}
	}
?>