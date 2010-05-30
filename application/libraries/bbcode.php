<?php
/**
 * BBCode Library
 *
 */
/**
 * http://svn.php.net/viewvc/pecl/bbcode/trunk/bbcode.php?view=markup
 * A complex BBCode Tags Parser
 *
 * The BBCode Array Arguments
 * childs
 * close_tag
 * content_handling
 * default_arg
 * flags > BBCode::FLAGS_*
 * open_tag
 * parents
 * param_handling
 * type > BBCode::TYPE_*
 */
Class BBCode{
	const TYPE_NOARG=		1;
	const TYPE_SINGLE=		2;
	const TYPE_ARG=			3;
	const TYPE_OPTARG=		4;
	const TYPE_ROOT=		10;

	// Flags
	const FLAGS_ARG_PARSING=			1;
	const FLAGS_CDATA_NOT_ALLOWED=		2;
	const FLAGS_SMILEYS_ON=				4;
	const FLAGS_SMILEYS_OFF=			8;
	const FLAGS_ONE_OPEN_PER_LEVEL=		16;
	/* Quote style accepted */
	/**
	 * Accepts args with no quotes ([tag=arg])
	 * Cannot be disabled
	 */
	const ARG_NO_QUOTE=0;
	/**
	 * Accepts args with double quotes ([tag="arg"])
	 */
	const ARG_DOUBLE_QUOTE=1;
	/**
	 * Accepts args with single quotes ([tag='arg'])
	 * one of the Double / Single quote is needed for subparsing
	 */
	const ARG_SINGLE_QUOTE=2;

	/**
	 * Enable autocorrection of HTML By closing "still opened tags"
	 *
	 * @var boolean
	 */
	public $auto_correct=true;
	/**
	 * If tags are incorrectly nested, they are closed and reopened
	 * after the previous tag close
	 *
	 * @var boolean
	 */
	public $correct_reopen_tags=true;
	/**
	 * Indicate wheter smileys are treated or not if no tag level 
	 * instructions override this
	 *
	 * @var boolean
	 */
	public $default_smileys=true;
	/**
	 * Indicates what styles of quotes are accepted
	 *
	 * @var int
	 */
	public $quote_styles;
	/**
	 * Disable BBCode Parsing
	 *
	 * @var boolean
	 */
	public $noTreeBuild=false;
	/**
	 * Disable Smileys
	 *
	 * @var boolean
	 */
	public $force_smileys_off=false;

	/**
	 * Privates Vars
	 *
	 * @var BBCode - if a specific parser is needed for argument parsing
	 */
	private $arg_parser=null;

	/**
	 * The internal working Tree
	 *
	 * @var Array
	 */
	private $tree;
	/**
	 * Lists Levels to access the root level
	 * @var array
	 */
	private $stack;
	/**
	 * An index to lists tags opened through the stack
	 * @var array
	 */
	private $stackIndex;
	/**
	 * The active Node
	 * @var array
	 */
	private $currentNode;
	/**
	 * Lists of unclosed stack
	 * @var array
	 */
	private $toClose;
	/**
	 * An index to lists unclosed tag
	 * @var array
	 */
	private $toCloseIndex;

	/**
	 * The List of tags (external representation)
	 *
	 * @var array
	 */
	private $tagList;
	/**
	 * The List of tags (internal representation)
	 *
	 * @var array
	 */
	private $tagListCache;
	/**
	 * The index to map TagName to tagid
	 *
	 * @var array
	 */
	private $tagListIndex;
	/**
	 * The list of smileys
	 *
	 * @var array
	 */
	private $smileys;

	private $close_time=0;
	private $add_time=0;

	/**
	 * The Constructor
	 *
	 * @param array $tag_init a list of BBCode for quick init
	 * @param array $smileys_init a list of smileys for quick init
	 */
	public function __construct($tag_init=null,$smileys_init=null){
	
		if (is_null($tag_init))
		{
			$tag_init=array(
''=>		array('open_tag'=>'',	'close_tag'=>'', 'type'=>BBCode::TYPE_ROOT),
'b'=>		array('type'=>BBCode::TYPE_NOARG,	'childs'=>'all',	'open_tag'=>'<b>',											'close_tag'=>'</b>'),
'u'=>		array('type'=>BBCode::TYPE_NOARG,	'childs'=>'all',	'smileys'=>false,	'open_tag'=>'<u>',											'close_tag'=>'</u>'),
'i'=>		array('type'=>BBCode::TYPE_NOARG,	'childs'=>'all',	'open_tag'=>'<i>',											'close_tag'=>'</i>'),
'code'=>	array('type'=>BBCode::TYPE_NOARG,	'childs'=>'u',		'open_tag'=>'<code>',					'close_tag'=>'</code>'),
'm'=>		array('type'=>BBCode::TYPE_NOARG,						'open_tag'=>'<a href="/maraboutage.php">',					'close_tag'=>'</a>'),
'cap'=>		array('type'=>BBCode::TYPE_NOARG,						'open_tag'=>'<span style="text-transform:capitalize;">',	'close_tag'=>'</span>'),
'mini'=>	array('type'=>BBCode::TYPE_NOARG,						'open_tag'=>'<span class="mini">',							'close_tag'=>'</span>'),
'right'=>	array('type'=>BBCode::TYPE_NOARG,						'open_tag'=>'<div style="text-align:right;">',				'close_tag'=>'</div>'),
'center'=>	array('type'=>BBCode::TYPE_NOARG,						'open_tag'=>'<div style="text-align:center;">',				'close_tag'=>'</div>'),
'just'=>	array('type'=>BBCode::TYPE_NOARG,						'open_tag'=>'<div style="text-align:justify;width:450px;margin:0 auto;">',				'close_tag'=>'</div>'),
'strike'=>	array('type'=>BBCode::TYPE_NOARG,						'open_tag'=>'<span style="text-decoration:line-through;">',	'close_tag'=>'</span>'),
'ancre'=>	array('type'=>BBCode::TYPE_NOARG,						'open_tag'=>'<a name="',									'close_tag'=>'"> &nbsp;</a>'),
'moumou'=>	array('type'=>BBCode::TYPE_NOARG,						'open_tag'=>'<img src="',									'close_tag'=>'" />'),
'round'=>	array('type'=>BBCode::TYPE_NOARG,						'open_tag'=>'<div style="border:2px double white;border-radius: 8px; padding:6px; -moz-border-radius: 8px; background-color:#000; color:#FFF;">',	'close_tag'=>'</div>'),
'hide'=>	array('type'=>BBCode::TYPE_NOARG,						'open_tag'=>'',												'close_tag'=>'',											'content_handling'=>'BBVoid'),
'np'=>		array('type'=>BBCode::TYPE_NOARG,						'open_tag'=>'',												'close_tag'=>''),
'quote'=>	array('type'=>BBCode::TYPE_OPTARG,	'flags'=>BBCode::FLAGS_ARG_PARSING ,	'open_tag'=>'<fieldset><legend>{ARG}</legend>',				'close_tag'=>'</fieldset>',	'default_arg'=>'Citation'),
'id'=>		array('type'=>BBCode::TYPE_OPTARG,	'arg_parse'=>false,	'open_tag'=>'',												'close_tag'=>'',			'default_arg'=>'{SELF}',		'content_handling'=>'BBId2User'),
'msg'=>		array('type'=>BBCode::TYPE_OPTARG,	'arg_parse'=>false,	'open_tag'=>'',												'close_tag'=>'',			'default_arg'=>'{SELF}',		'content_handling'=>'BBId2Msg'),
'url'=>		array('type'=>BBCode::TYPE_OPTARG,	'arg_parse'=>false,	'open_tag'=>'<a href="{ARG}">',								'close_tag'=>'</a>',		'default_arg'=>'{CONTENT}',		'arg_handling'=>'validate_url'),
'img'=>		array('type'=>BBCode::TYPE_OPTARG,	'arg_parse'=>false,	'open_tag'=>'<img src="{ARG}" alt="',						'close_tag'=>'" />',		'default_arg'=>'{CONTENT}'),
'mail'=>	array('type'=>BBCode::TYPE_OPTARG,	'arg_parse'=>false,	'open_tag'=>'<a href="mailto:{ARG}">',						'close_tag'=>'</a>',		'default_arg'=>'{CONTENT}'),
'border'=>	array('type'=>BBCode::TYPE_OPTARG,	'arg_parse'=>false,	'open_tag'=>'<div style="border:{ARG}px solid;">',			'close_tag'=>'</div>',		'default_arg'=>'5'),
'list'=>	array('type'=>BBCode::TYPE_OPTARG,	'arg_parse'=>false,	'open_tag'=>'<ol type="{ARG}">',							'close_tag'=>'</ol>',		'default_arg'=>'1',				'content_handling'=>'BBlist'),
'*'	=>		array('type'=>BBCode::TYPE_NOARG,	'flags'=>BBCode::FLAGS_ONE_OPEN_PER_LEVEL,'parents'=>'list',	'open_tag'=>'<li>',					'close_tag'=>'</li>',),
'col'=>		array('type'=>BBCode::TYPE_OPTARG,	'arg_parse'=>false,	'open_tag'=>'<div style="-moz-column-count:{ARG}; text-align:justify;-moz-column-gap:8px;">',	'close_tag'=>'</div>',		'default_arg'=>'2'),
'hexdump'=>	array('type'=>BBCode::TYPE_OPTARG,	'arg_parse'=>false,	'open_tag'=>'[Hex Dump]<br />',								'close_tag'=>'',			'default_arg'=>'0x345622',		'content_handling'=>'BBHexDump'),
'bcktrc'=>	array('type'=>BBCode::TYPE_OPTARG,	'arg_parse'=>false,	'open_tag'=>'',												'close_tag'=>'',			'default_arg'=>'0x345622',		'content_handling'=>'BBBackTrace'),
'bong'=>	array('type'=>BBCode::TYPE_OPTARG,	'arg_parse'=>false,	'open_tag'=>'<div id="{ARG}">',								'close_tag'=>'</div><script type="text/javascript">new OngletsBlock(\'{ARG}\');</script>','default_arg'=>'Fiche'),
'onglet'=>	array('type'=>BBCode::TYPE_ARG,		'arg_parse'=>false,	'open_tag'=>'<h4>{ARG}</h4><div title="{ARG}" closable="true" style="clear:both;">','close_tag'=>'</div>','default_arg'=>'Default'),
'color'=>	array('type'=>BBCode::TYPE_ARG,		'arg_parse'=>false,	'open_tag'=>'<span style="color:{ARG}">',					'close_tag'=>'</span>'),
'size'=>	array('type'=>BBCode::TYPE_ARG,		'arg_parse'=>false,	'open_tag'=>'<span style="font-size:{ARG}px;">',			'close_tag'=>'</span>'),
'goto'=>	array('type'=>BBCode::TYPE_ARG,		'arg_parse'=>false,	'open_tag'=>'<a href="#{ARG}" onmouseover="this.href=document.location.href.replace(/#.*/img ,\'\')+\'#{ARG}\'">', 'close_tag'=>'</a>'),
'fade'=>	array('type'=>BBCode::TYPE_ARG,		'arg_parse'=>false,	'open_tag'=>'',												'close_tag'=>'',											'content_handling'=>'BBDegrade'),
'wave'=>	array('type'=>BBCode::TYPE_ARG,		'arg_parse'=>false,	'open_tag'=>'',												'close_tag'=>'',											'content_handling'=>'BBWave'),
'barre'=>	array('type'=>BBCode::TYPE_SINGLE,						'open_tag'=>'<hr />'),
);
		}
		$this->tagList=$this->smileys=array();
		if (is_array($tag_init)){
			$this->tagList=$tag_init;
			$this->tagListCache=null;
		}

		if (is_array($smileys_init)){
			$this->smileys=$smileys_init;
		}
		$this->quote_styles=self::ARG_NO_QUOTE|self::ARG_DOUBLE_QUOTE|self::ARG_SINGLE_QUOTE;
		$this->tree=null;
	}
	/**
	 * Attach a parser as argument sub-parser
	 *
	 * @param BBCode $bbcode_parser
	 */
	public function set_arg_parser($bbcode_parser){
		$this->arg_parser=null;
		if ($bbcode_parser instanceof BBCode){
			$this->arg_parser=$bbcode_parser;
		}
	}
	/**
	 * This function is there to add an individual tag to the list
	 *
	 * @param string $tag_name
	 * @param array $array
	 */
	public function add_element($tag_name,$array){
		$this->tagList[$tag_name]=$array;
		$this->tagListCache=null;
	}
	/**
	 * Adds an array smiley or an individual smiley to the list
	 *
	 * @param string $smiley
	 * @param string $replacement
	 */
	public function attach_smileys($smiley, $replacement = null){
		if(!is_array($smiley))
		{
			$this->smileys[$smiley] = $replacement;
		}
		else
		{
			$this->smileys = array_merge($this->smileys, $smiley);
		}
		
	}

	/**
	 * Parse a string from BBCode following the rules given
	 * 
	 * @param String $string
	 * @return string
	 */
	public function parse($string){
		// Init
		$this->toCloseIndex=$this->toClose=$this->stackIndex=$this->stack=array();
		$this->tree=null;
		if ($this->noTreeBuild){
			$this->tree=array('i'=>$this->get_tag_id(''),'p'=>true,'s'=>'');
			$this->tree['c'][] = $string;
		} else {
			$this->prepareTagList();
			// Tree construction
			$this->build_tree($string);
			// Tree corrections and fetch
			$this->correct_tree();
		}
		// Conversion
		return $this->apply_rules($this->tree);
	}

	/**
	 * Convert external representation of the taglist to internal
	 *
	 */
	private function prepareTagList(){
		if ($this->tagListCache!=null){
			return;
		}
		if (!isset($this->tagList[''])){
			$this->tagList['']=array();
		}
		$i=0;
		// 1. Populate the tagListCache
		$all=array();
		foreach($this->tagList as $tag=>$tag_datas){
			$all[]=$i;
			$this->tagListIndex[$tag]=$i;
			/**
			 * Internal fields:
			 * 
			 *  0 	'type'
			 *  1	'open_tag'
			 *  2	'close_tag'
			 *  3	'default_arg'
			 *  4	'flags'
			 *  5	'parents'
			 *  6	'childs'
			 *  7	'content_handling'
			 *  8	'content_handling_is_callback'
			 *  9 	'param_handling'
			 * 10	'param_handling_is_callback'
			 * 11	'accept_arg'
			 * 12	'accept_noarg'
			 * 13	'start_has_bracket_open'
			 * 14	'end_has_bracket_open'
			 * 15	'accept_smileys'
			 */
			$type=isset($tag_datas['type'])?$tag_datas['type']:BBCODE_TYPE_NOARG;
			$start=isset($tag_datas['open_tag'])?$tag_datas['open_tag']:'';
			$end=isset($tag_datas['close_tag'])?$tag_datas['close_tag']:'';
			$flags=isset($tag_datas['flags'])?$tag_datas['flags']:0;
			$this->tagListCache[$i]=array(
			$type,
			$start,
			$end,
			isset($tag_datas['default_arg'])?$tag_datas['default_arg']:'',
			$flags,
			isset($tag_datas['parents'])?$tag_datas['parents']:'all',
			isset($tag_datas['childs'])?$tag_datas['childs']:'all',
			isset($tag_datas['content_handling'])?$tag_datas['content_handling']:'',
			null,
			isset($tag_datas['param_handling'])?$tag_datas['param_handling']:'',
			null,
			(bool)($type==BBCode::TYPE_ARG || $type==BBCode::TYPE_OPTARG),
			(bool)($type==BBCode::TYPE_NOARG || $type==BBCode::TYPE_OPTARG || $type==BBCode::TYPE_SINGLE),
			(bool)(strpos($start,"{")),
			(bool)(strpos($end,"{")),
			((($flags&(BBCode::FLAGS_SMILEYS_OFF|BBCode::FLAGS_SMILEYS_ON))==0)&& $this->default_smileys ) || $flags&(BBCode::FLAGS_SMILEYS_ON),
			);
			
			
			++$i;
		}
		
		// 2. Parse Child / Parents List
		foreach($this->tagListCache as $i=>$val){
			// Childs
			if ($val[6]=='all'){
				$val[6]=$all;
			} elseif ($val[6]==''){
				$val[6]=array();
			} elseif ($val[6][0]=='!'){
				$values=explode(',',substr($val[6],1));
				$val[6]=$all;
				foreach ($values as $element){
					if($tag_id=$this->get_tag_id($element)){
						$pos=array_search($tag_id,$val[6]);
						unset($val[6][$pos]);
					}
				}
			} else {
				$values=explode(',',$val[6]);
				foreach ($values as $element){
					$val[6]=array();
					if($tag_id=$this->get_tag_id($element)){
						$val[6][]=$tag_id;
					}
				}
			}
			//Parents
			if ($val[5]=='all'){
				$val[5]=$all;
			} elseif ($val[5]==''){
				$val[5]=array();
			} elseif ($val[5][0]=='!'){
				$val[5]=$all;
				$values=explode(',',substr($val[5],1));
				foreach ($values as $element){
					$val[5]=array();
					if($tag_id=$this->get_tag_id($element)){
						$pos=array_search($val[5],$tag_id);
						unset($val[5][$pos]);
					}
				}
			} else {
				$values=explode(',',$val[5]);
				foreach ($values as $element){
					$val[5]=array();
					if($tag_id=$this->get_tag_id($element)){
						$val[5][]=$tag_id;
					}
				}
			}
			$this->tagListCache[$i]=$val;
		}
	}

	/**
	 * Build the tree representation of the given string
	 *
	 * @param string $string
	 */
	private function build_tree($string){
		$strlen=strlen($string);
		$quote_double=(bool)$this->quote_styles&self::ARG_DOUBLE_QUOTE;
		$quote_single=(bool)$this->quote_styles&self::ARG_SINGLE_QUOTE ;
		$end_quote="";
		$end=0;
		$this->tree=array('i'=>$this->get_tag_id(''),'p'=>true,'s'=>'');
		$this->currentNode =& $this->tree;
		// Init Working Var
		$next_close=$next_equal=0;
		$offset=strpos($string,'[');
		$this->add_child(substr($string,0,$offset));
		do {
			$added=false;
			if ($string[$offset]=='['){
				if (($string[$offset+1])!='/'){
					//Open
					// Equal
					if ($next_equal<=$offset){
						if (false===$next_equal=strpos($string,'=',$offset)){
							$next_equal=$strlen+5;
						}
					}
					if ($next_close<=$offset){
						if (false===$next_close=strpos($string,']',$offset)){
							$next_close=$strlen+5;
						}
					}
					if ($next_close<$strlen){
						// With Arg
						if ($next_equal<$next_close){
							$tag=substr($string,$offset+1,$next_equal-$offset-1);
							if (false!==($tagId=$this->get_tag_id($tag,true))){
								$argument=false;
								if ($this->quote_styles>0){
									$end_quote='';
									$end=$next_close;
									if ($quote_single && $string[$next_equal+1]=="'"){
										$end_quote="'";
									} elseif ($quote_double && $string[$next_equal+1]=='"') {
										$end_quote='"';
									}
									if ($end_quote==''){
										$argument=substr($string,$next_equal+1,$next_close-$next_equal-1);
									} else {
										if (false!==$end=strpos($string,"$end_quote]",$next_equal+1)){
											$argument=substr($string,$next_equal+2,$end++ - $next_equal - 2);
										} else {
											$end=$strlen+5;
										}
									}
								} else {
									$argument=substr($string,$next_equal+1,$next_close-$next_equal-1);
								}
								if ($argument!==false){
									$this->add_child(substr($string,$offset,$end-$offset+1),$tagId,$argument);
									$added=true;
								}
							} else {
								$end=$next_close;
							}
						} else {
							// Without Args
							$tag=substr($string,$offset+1,$next_close-$offset-1);
							$end=$next_close;
							if (false!==($tagId=$this->get_tag_id($tag,false))){
								$this->add_child(substr($string,$offset,$end-$offset+1),$tagId);
								$added=true;
							}
						}
					}
				} else {
					if ($next_close<=$offset){
						if (false===($next_close=strpos($string,']',$offset))){;
						$next_close=$strlen+5;
						}
					}
					//Close
					$tag=substr($string,$offset+2,$next_close-$offset-2);
					$end=$next_close;
					if (false!==($tagId=$this->get_tag_id($tag))){
						$this->close_tag($tagId,substr($string,$offset,$end-$offset+1));
						$added=true;
					}
				}
			}
			if (!$added){
				$end=strpos($string,'[',$offset+1);
				if ($end==false){
					$end=$strlen;
				} else {
					--$end;
				}
				$this->add_child(substr($string,$offset,$end-$offset+1));
			}
			$offset=$end+1;
		} while($offset<$strlen);
	}

	/**
	 * Adds a child to the tree
	 *
	 * @param string $string
	 * @param int $tagId
	 * @param string $argument
	 */
	private function add_child($string,$tagId=null,$argument=null){
		if ($tagId===null){
			// Only a textNode => Adding to childs
			$this->currentNode['c'][]=$string;
		} else {
			$tag=$this->tagListCache[$tagId];
			// Auto Closing some elements
			if ($tag[4]&BBCode::FLAGS_ONE_OPEN_PER_LEVEL){
				//Fixme Multiple parents cases
				$index=array_reverse($this->stackIndex);
				array_unshift($index,$this->currentNode['i']);
				$parent_pos=PHP_INT_MAX;
				foreach($tag[5] as $poss_parent){
					$parent_pos=min(array_search($poss_parent,$index),$parent_pos);
				}
				if (false!==($last_el_pos=array_search($tagId,$index))){
					if ($parent_pos<$last_el_pos){
						// Return to parent
						for($i=0;$i<$parent_pos;++$i){
							$this->close_tag($index[$i],'',false);
						}
					} else {
						// Close last element
						$this->close_tag($tagId,'');
					}
				}
			}
			// Creating Node
			$new_node=array('i'=>$tagId,'c'=>array(),'s'=>$string,'a'=>$argument===null?'':$argument,'p'=>false);
			$this->currentNode['c'][] =& $new_node;
			$this->stack[] =& $this->currentNode;
			$this->currentNode =& $new_node;
			$this->stackIndex[]=$tagId;
			if ($tag[0]==BBCode::TYPE_SINGLE){
				$this->close_tag($tagId,'');
			}
		}
	}

	/**
	 * Closes a tag
	 *
	 * @param int $tag_id
	 * @param string $string
	 * @param boolean $true_close
	 */
	private function close_tag($tag_id, $string,$true_close=true){
		if (in_array($tag_id,$this->toCloseIndex)){
			$closeIndex = array_search($tag_id, $this->toCloseIndex);
			$this->toClose[$closeIndex]['p']=true;
			array_splice($this->toClose,$closeIndex,1);
			array_splice($this->toCloseIndex,$closeIndex,1);
		} elseif (in_array($tag_id,$this->stackIndex)){
			// Tag Open
			$searching=true;
			$conds=array();
			do{
				if ($this->currentNode['i']==$tag_id){
					$searching=false;
					// Mark this node as paired (we found close tag)
					$this->currentNode['p']=$true_close;
					$this->currentNode['cs']=$string;
					$this->currentNode['cond'] =& $conds;
					if (isset($this->currentNode['mp'])){
						$this->currentNode['l']=true;
					}
					if (!$true_close){
						$this->toClose[] =& $this->currentNode;
					}
				} else {
					if (!in_array($tag_id,$this->tagListCache[$this->currentNode['i']][6])){
						$conds[] =& $this->currentNode;
					}
					$this->currentNode['cs']='';
					if ($this->tagListCache[$this->currentNode['i']][4]&BBCode::FLAGS_ONE_OPEN_PER_LEVEL){
						$this->currentNode['p'] = true;
					} else {
						$this->currentNode['p'] = false;
						$this->toClose[] =& $this->currentNode;
						$this->toCloseIndex[] = $this->currentNode['i'];
					}
				}
				array_pop($this->stackIndex);
				$this->currentNode =& $this->stack[count($this->stack)-1];
				array_pop($this->stack);
			} while($searching);
			if ($this->correct_reopen_tags){
				$new_node = array();
				for($i=count($this->toClose)-1;$i>=0;--$i){
					if (!isset($this->toClose[$i]['mp'])){
						$this->toClose[$i]['d'] = false;
						$this->toClose[$i]['mp'] = array(& $this->toClose[$i]);
					}
					$new_node[$i] = array(
					'i'=>$this->toClose[$i]['i'],
					's'=>'', 'cs'=>'',
					'p'=>& $this->toClose[$i]['p'],
					'd'=>& $this->toClose[$i]['d'],
					'mp'=>& $this->toClose[$i]['mp'],
					'par'=>array()
					);
					if (isset($this->toClose[$i]['a'])){
						$new_node[$i]['a']=$this->toClose[$i]['a'];
					}
					$new_node[$i]['mp'][] =& $new_node;
					for ($j=$i-1;$j>=0;--$j){
						$new_node[$i]['par'][] =& $this->toClose[$j];
					}
					$this->currentNode['c'][] =& $new_node[$i];
					$this->stack[] =& $this->currentNode;
					$this->currentNode =& $new_node[$i];
					$this->stackIndex[] = $this->toClose[$i]['i'];
				}
				$this->toCloseIndex=$this->toClose=array();
			}
		} else {
			// Tag Not Open
			$this->add_child($string);
		}
	}
	/**
	 * Validates a tree and applying Childs / Parent Rules
	 *
	 * @param array $tree
	 * @param int $parent
	 * @param boolean $force_false
	 * @return array
	 */
	private function correct_tree(&$tree=null,$parent=0,$force_false=false){
		/* Getting all options localy to use local symbol table */
		$restore_tree=false;
		/* Fetching tree if using object internal tree */
		if (is_null($tree)){
			$tree =& $this->tree;
			$restore_tree=true;
		}
		/* fetch auto_correct localy */
		$ac=$this->auto_correct;
		/* Starting new tree for corrected version */
		$new_tree=array();
		/* fetch tag localy */
		$tag=$this->tagListCache[$tree['i']];
		/* Check if parent is in parent_allow_list */
		if (!in_array($parent,$tag[5])){
			$force_false=true;
		}
		if ($force_false){
			/* if force_false is enabled, unpair tags */
			$tree['p']=false;
		} elseif (isset($tree['i']) && $tag[4]&BBCode::FLAGS_ONE_OPEN_PER_LEVEL){
			/* If this tag has the special flag ONE_OPEN_PER_LEVEL, force pairing */ 
			$tree['p']=true;
		}
		if (!$ac && !$tree['p']){
			/* If no auto_correction neither pairing, force false */
			$force_false=true;
		}
		/* Fetch the parent, according to force_false setting */
		$orig_parent=$parent;
		$parent=$force_false?$parent:$tree['i'];
		/* If some elements has some conditions over other elements */
		if (isset($tree['cond'])){
			/* searching trough conditions lists */
			foreach ($tree['cond'] as $element){
				if (!$element['p']){
					continue;
				} else {
					/* If one condition is paired, this element can not exists */
					$tree['p']=false;
				}
			}
		}
		/* Forcing child_list presence */
		if (!isset($tree['c'])){
			$tree['c']= array();
		}
		/* Browsing child_list for corrections */
		for($i=0; $i<count($tree['c']);++$i){
			$child =& $tree['c'][$i];
			/* Subtree */
			if (is_array($child)){
				/* If the child is a multipart and not done */
				if (isset($child['mp']) && !$child['d']){
					//Multipart Child
					foreach($child['mp'] as $child_part){
						/* TODO Comments */
						if (is_array($child_part)){
							if (!isset($child_part['i'])){
								continue;
							}
							/* If one child has parents and if one parent is unpaired mark as unpaired */
							if (isset($child_part['par'])){
								for($j=0;$j<count($child_part['par']);++$j){
									if ($child_part['par'][$j]['p']){
										continue;
									}
									$tree['p']=false;
									break;
								}
							} else {
								/* Check for mutual inclusion as child else, mark as unpaired */
								/* TODO: Check if the original way was not parent / child checking */ 
								if (in_array($child_part['i'],$this->tagListCache[$parent][6])){
									if (in_array($parent,$this->tagListCache[$child_part['i']][6])){
										continue;
									}
								}
								$tree['p']=false;
								break;
							}
						}
					}
					/* Mark Child as done */
					$child['d']=true;
					/* If no autocorrection & unpaired */
					if (!$ac && !$tree['p']){
						/* Force False */
						$force_false=true;
					}
					/* Get Parent for allow_list resolv */
					$parent=$force_false?$orig_parent:$tree['i'];
					/* Checking allow_list */
					if (in_array($child['i'], $this->tagListCache[$parent][6])){
						/* The tag can be a subelement of this one */
						$return =& $this->correct_tree($child, $parent, false);
					} else {
						/* The tag can not be a subelement of this one */
						$return =& $this->correct_tree($child, $parent, true);
					}
				} elseif (in_array($child['i'], $this->tagListCache[$parent][6])){
					/* The tag can be a subelement of this one */
					$return =& $this->correct_tree($child, $parent, false);
				} else {
					/* The tag cannot be a subelement of this one */
					$return =& $this->correct_tree($child, $parent, true);
				}
				/* Appending return(s) to new_tree */
				array_splice($new_tree, count($new_tree), 0, $return);
			} else {
				/* String */
				$new_tree[] =& $child;
			}
		}
		if (!$force_false && ($tree['p'] || $ac)){
			/* If tree is correct or autocorrection is ON */
			/* Replace Child List with corrected one */
			$tree['c'] =& $new_tree;
			// Multipart_Join
			/* Return data */
			if ($restore_tree){
				$tree['c'] =& $new_tree;
			} else {
				return array(&$tree);
			}
		} else {
			/* If force false or no autocorrection and unpaired tag */
			if ($force_false){
				/* If force false flag is set, force unpair */
				$tree['p']=false;
			}
			/* prepend the current open String to corrected Child_list */
			array_unshift($new_tree,$tree['s']);
			/* append the closing string to corrected child list */
			$new_tree[] =& $tree['cs'];
			/* Return data */
			if ($restore_tree){
				$this->tree =& $new_tree;
			} else {
				return $new_tree;
			}
		}
	}
	/**
	 * Apply the ruleset to transform BBCode tree to Markup
	 *
	 * @param array $tree
	 * @return string
	 */
	private function apply_rules($tree){
		//Fetch datas locally - Performance
		$default_smileys=$this->default_smileys;
		$auto_correct=$this->auto_correct;
		$string="";
		$tag=array();
		if (isset($tree['i'])){
			$tag=$this->tagListCache[$tree['i']];
		}
		$last_string='';
		for ($i=0;$i<count($tree['c']);++$i){
			if (is_array($tree['c'][$i]) && isset($tree['c'][$i]['mp'])){
				for($j=$i+1;$j<count($tree['c']);++$j){
					if (is_array($tree['c'][$j]) && isset($tree['c'][$j]['mp'])){
						$tree['c'][$j]['mp'][0]['ref_test_key']='TEST';
						if (isset($tree['c'][$i]['mp'][0]['ref_test_key'])){
							for ($k=$i+1;$k<$j;++$k){
								$tree['c'][$i]['c'][] =& $tree['c'][$k];
							}
							for ($k=0;$k<count($tree['c'][$j]['c']);++$k){
								$tree['c'][$i]['c'][] =& $tree['c'][$j]['c'][$k];
							}
							$tree['c'][$i]['cs']=$tree['c'][$j]['cs'];
							unset($tree['c'][$j]['mp'][0]['ref_test_key']);
							array_splice($tree['c'],$i+1,$j-$i);
							$i=0;
							continue 2;
						} else {
							unset($tree['c'][$j]['mp'][0]['ref_test_key']);
							break;
						}
					}
				}
			}
		}
		foreach($tree['c'] as $child){
			if (is_array($child)){
				//Make a one block treatment for smileys
				if (strlen($last_string)){
					if (!$this->force_smileys_off && $tag[15]){
						$string.=$this->smileys($last_string);
					} else {
						$string.=$last_string;
					}
				}
				$string.=$this->apply_rules($child);
				$last_string='';
			} else {
				//Concat String for Smiley treatment
				$last_string.=$child;
			}
		}
		//Make a one block treatment for smileys
		if (strlen($last_string)){
			if (!$this->force_smileys_off && $tag[15]){
				$string.=$this->smileys($last_string);
			} else {
				$string.=$last_string;
			}
		}
		if (!($auto_correct || $tree['p'] || ($tag[4]&BBCode::FLAGS_ONE_OPEN_PER_LEVEL))){
			return ($tree['s']).$string;
		} else {
			$start=$tag[1];
			$end=$tag[2];

			$arg='';
			if ($tag[11]){
				if ($tree['a']!=''){
					$arg=$tree['a'];
				} else {
					$arg=$tag[3];
				}
				if ($tag[4] & BBCode::FLAGS_ARG_PARSING){
					if ($this->arg_parser!==null){
						$arg=$this->arg_parser->parse($arg);
					} else {
						$arg=$this->parse($arg);
					}
				}
			}
			/* Search For CallBacks */
			
			if ($cb=$this->get_callback($tree['i'],true)){
				$arg=$cb($string,$arg);
			}
			if ($cb=$this->get_callback($tree['i'],false)){
				$string=$cb($string,$arg);
			}
			/* Replacing {ARG} by $arg and {CONTENT} by $string in arg & start */
			$arg =str_replace("{CONTENT}",$string,$arg);
			if ($tag[13]){
				$start=str_replace("{CONTENT}",$string,$start);
				$start=str_replace("{ARG}",$arg,$start);
			}

			/* Replacing {ARG} by $arg in string & end */
			$string =str_replace("{ARG}",$arg,$string);
			if ($tag[14]){
				$end=str_replace("{ARG}",$arg,$end);
			}

			return $start.$string.$end;
		}
	}
	/**
	 * Returns a callback for a tag_id
	 * 
	 * @param int $tagid
	 * @param bolean $is_arg
	 * @return callback
	 */
	private function get_callback($tagid,$is_arg){
		$tag = $this->tagListCache[$tagid];
		
		if ($is_arg){
			if ($tag[10]===null){
				$this->tagListCache[$tagid][10]=is_callable($tag[9])?$tag[9]:false;
			}
			return $this->tagListCache[$tagid][10];
		} else {
			if ($tag[8]===null){
				$this->tagListCache[$tagid][8]=is_callable($tag[7])?$tag[7]:false;
			}
			return $this->tagListCache[$tagid][8];
		}
	}
	/**
	 * Treats smileys
	 * 
	 * @param string $smileys
	 * @return string
	 */
	private function smileys($string){
		$keys=array_keys($this->smileys);
		return str_replace($keys,$this->smileys,$string);
	}
	/**
	 * Returns a tag_id from his name (tag_id is internal)
	 * 
	 * @param string $tag_name
	 * @param boolean $has_args
	 * @return string
	 */
	private function get_tag_id($tag_name, $has_arg=null){
		$tag_name=strtolower($tag_name);
		if (isset($this->tagListIndex[$tag_name])){
			$tag_id=$this->tagListIndex[$tag_name];
			if ($has_arg===null){
				return $tag_id;
			} elseif ($has_arg===false){
				if ($this->tagListCache[$tag_id][12]===true){
					return $tag_id;
				}
			} else {
				if ($this->tagListCache[$tag_id][11]===true){
					return $tag_id;
				}
			}
		}
		return false;
	}
}
/*
$bbcode=new BBCode($arrayBBCode,array(':)'=>'\\o/',':|'=>'/o\\'));
$bbcode->auto_correct=false;
$bbcode->correct_reopen_tags=true;
$time=microtime(true);
$string=<<<EOF
[list][*]List[list][*]SubList[/list][/list]
EOF;
echo $bbcode->parse($string)."\n";
$val[]=microtime(true)-$time;
echo (microtime(true)-$time)."\n";
$time=microtime(true);
$string=<<<EOF
UUU[quote="[u]:) :|UUU[/u]"][b]:)Te[b]st[/b][/quote]AAAAA
[LIST=0][*]Test
TEst
[*][list=a][*]Coucou
[/list]
[/list]
EOF;
echo $bbcode->parse($string)."\n";
$val[]=microtime(true)-$time;
echo (microtime(true)-$time)."\n";
$time=microtime(true);
$string=<<<EOF
[b][u]Hello[/b] W[i]o[b]r [b]ld[/u] Coucou[/i] Vro[/b] Vroum
EOF;
echo $bbcode->parse($string)."\n";
$val[]=microtime(true)-$time;
echo (microtime(true)-$time)."\n";
$time=microtime(true);
$string=<<<EOF
[list][*]List[list][*]SubList[*]element2[list][*]SubSubList[/list][/list][*]element 3[/list]
EOF;
echo $bbcode->parse($string)."\n";
$val[]=microtime(true)-$time;
echo (microtime(true)-$time)."\n";
$time=microtime(true);
$string=<<<EOF
[list][*]List[list][*]SubList[/list][/list]
EOF;
echo $bbcode->parse($string)."\n";
$val[]=microtime(true)-$time;
echo (microtime(true)-$time)."\n";
$time=microtime(true);
$string=<<<EOF
[b]Test[u]Test[b] [i] Glup [u] [*][*][barre][i]Glop[/i] [/u][/i]Zop[/b] [/u][/b]
EOF;
echo $bbcode->parse($string)."\n";
$val[]=microtime(true)-$time;
echo (microtime(true)-$time)."\n";
$time=microtime(true);
//$bbcode->noTreeBuild=true;
$bbcode->default_smileys=true;
$string=<<<EOF
[b][i] [code] [u][/i][/b][b][/u] [/code] [u] Test [/b][/u]
EOF;
echo $bbcode->parse($string)."\n";
$val[]=microtime(true)-$time;
echo (microtime(true)-$time)."\n";
echo array_sum($val);
*/

function namana_bbcode_root_function($ret)
{
	$ret = preg_replace("#(^|[\n ])([\w]+?://[\w]+[^ \"\n\r\t< ]*)#", "\\1<a href=\"\\2\" target=\"_blank\">\\2</a>", $ret);
	$ret = preg_replace("#(^|[\n ])((www|ftp)\.[^ \"\t\n\r< ]*)#", "\\1<a href=\"http://\\2\" target=\"_blank\">\\2</a>", $ret);
	$ret = preg_replace("/([a-zA-Z0-9-_]+)@([a-zA-Z0-9-_]+)/", "\\1-at-\\2", $ret);
	$ret = preg_replace("/@(\w+)/", "<a href=\"http://namana.serasera.org/profile/\\1\" target=\"_blank\">@\\1</a>", $ret);
	$ret = preg_replace("/#(\w+)/", "<a href=\"http://search.twitter.com/search?q=\\1\" target=\"_blank\">#\\1</a>", $ret);
	return $ret;
}
