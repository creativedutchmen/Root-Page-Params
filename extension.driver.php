<?php

	require_once(TOOLKIT . '/class.entrymanager.php');
	
	
	Class extension_map_to_front extends Extension{
	
		protected $section_data;
		protected $_page;
		protected $static_section_name;
		
		static $alreadyRan = false;
		
		public function about(){
			return array('name' => 'Map to front',
						 'version' => '1.0',
						 'release-date' => '2009-10-05',
						 'author' => array('name' => 'Huib Keemink',
										   'website' => 'http://www.creativedutchmen.com',
										   'email' => 'huib@creativedutchmen.com')
				 		);
		}
		
		public function getSubscribedDelegates(){
			return array(
						array(
							'page' => '/frontend/',
							'delegate' => 'FrontendPrePageResolve',
							'callback' => 'addPage'
						)
			);
		}
		
		public function addPage(&$context){
		
			//to prevent the callback loop
			if(!$this->alreadyRan){
				$this->alreadyRan = true;
				//the only way to access the current (active) pages.
				$front = FrontEnd::Page();
				
				if(!$front->resolvePage($context['page'])){
					$indexPage = $this->__getIndexPage();
					$indexHandle = $indexPage['handle'];
					
					//adds the home page to the handle, if the current page is not found.
					//requires the home page to fallback to a 404 if the params do not match, otherwise no 404 error will ever be created.
					$context['page'] = $indexHandle.'/'.$context['page'];
				}
			}
			
		}
		
		//any way to get this without using the database?
		function __getIndexPage(){
			$row = $this->_Parent->Database->fetchRow(0, "SELECT `tbl_pages`.* FROM `tbl_pages`, `tbl_pages_types` 
															  WHERE `tbl_pages_types`.page_id = `tbl_pages`.id 
															  AND tbl_pages_types.`type` = 'index' 
															  LIMIT 1");
			return $row;
		}
		
	}

?>
