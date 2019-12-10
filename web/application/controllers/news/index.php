<?php
class indexController extends Controller {
	private $limit = 20;
	public function index($page = 1) {
		$this->load->checkLicense();
		$this->document->setActiveSection('news');
		$this->document->setActiveItem('index');

		$this->load->library('pagination');
		$this->load->model('news');
		$this->load->model('users');
		
		if($this->user->getNews() > 0) {
			$userid = $this->user->getId();
			$this->usersModel->updateUser($userid, array('user_news' => 0));
			$this->user->clearNews();
		}
		
		$sort = array(
			'news_date_add'	=> 'DESC'
		);
		
		$options = array(
			'start'		=>	($page - 1) * $this->limit,
			'limit'		=>	$this->limit
		);
		
		$total = $this->newsModel->getTotalNews();
		$news = $this->newsModel->getNews(array(), array(), $sort, $options);
		
		$paginationLib = new paginationLibrary();
		
		$paginationLib->total = $total;
		$paginationLib->page = $page;
		$paginationLib->limit = $this->limit;
		$paginationLib->url = $this->config->url . 'news/index/index/{page}';
		
		$pagination = $paginationLib->render();
		
		$this->data['news'] = $news;
		$this->data['pagination'] = $pagination;
		
		$this->getChild(array('common/header', 'common/footer'));
		return $this->load->view('news/index', $this->data);
	}
}
?>
