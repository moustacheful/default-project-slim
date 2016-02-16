<?
class TwigFilters extends \Twig_Extension
{
	public $filters = array();
	public function __construct(){

		$this->filters = array(

			'd' => function($input){
				return d($input);
			},

			'json' => function($obj){
				return json_encode($obj);
			}

		);
	}
	public function getName(){
		return 'twig_filters';
	}
	public function getFilters(){
		$filters = array_map(function($filterName,$filterCallable){
			return new Twig_SimpleFilter($filterName,$filterCallable);
		},array_keys($this->filters),$this->filters);

		return $filters;
	}
}