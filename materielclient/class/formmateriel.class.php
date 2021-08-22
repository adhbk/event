<?php
//Modif
class FormGroupeMateriel extends Form{


	/**
	 * Generic method to select a component from a combo list.
	 * This is the generic method that will replace all specific existing methods.
	 *
	 * @param 	string			$objectdesc			Objectclassname:Objectclasspath
	 * @param	string			$htmlname			Name of HTML select component
	 * @param	int				$preselectedvalue	Preselected value (ID of element)
	 * @param	string			$showempty			''=empty values not allowed, 'string'=value show if we allow empty values (for example 'All', ...)
	 * @param	string			$searchkey			Search criteria
	 * @param	string			$placeholder		Place holder
	 * @param	string			$morecss			More CSS
	 * @param	string			$moreparams			More params provided to ajax call
	 * @param	int				$forcecombo			Force to load all values and output a standard combobox (with no beautification)
	 * @return	string								Return HTML string
	 * @see selectForFormsList() select_thirdparty
	 */
    public function selectForFormsMateriel($objectdesc, $htmlname, $preselectedvalue, $showempty = '', $searchkey = '', $placeholder = '', $morecss = '', $moreparams = '', $forcecombo = 0, $isClient,$isFournisseur)
	{

        //print here;
		global $conf, $user;

		$objecttmp = null;

		$InfoFieldList = explode(":", $objectdesc);
		$classname=$InfoFieldList[0];
		$classpath=$InfoFieldList[1];
		if (! empty($classpath))
		{
			dol_include_once($classpath);
			if ($classname && class_exists($classname))
			{
				$objecttmp = new $classname($this->db);
			}
		}
		if (! is_object($objecttmp))
		{
			dol_syslog('Error bad setup of type for field '.$InfoFieldList, LOG_WARNING);
			return 'Error bad setup of type for field '.join(',', $InfoFieldList);
		}

		$prefixforautocompletemode=$objecttmp->element;
		if ($prefixforautocompletemode == 'societe') $prefixforautocompletemode='company';
		$confkeyforautocompletemode=strtoupper($prefixforautocompletemode).'_USE_SEARCH_TO_SELECT';	// For example COMPANY_USE_SEARCH_TO_SELECT

		dol_syslog(get_class($this)."::selectForForms", LOG_DEBUG);

		$out='';
		if (! empty($conf->use_javascript_ajax) && ! empty($conf->global->$confkeyforautocompletemode) && ! $forcecombo)
		{
			$objectdesc=$classname.':'.$classpath;
			$urlforajaxcall = DOL_URL_ROOT.'/core/ajax/selectobject.php';

			// No immediate load of all database
			$urloption='htmlname='.$htmlname.'&outjson=1&objectdesc='.$objectdesc.($moreparams?$moreparams:'');
			// Activate the auto complete using ajax call.
			$out.=  ajax_autocompleter($preselectedvalue, $htmlname, $urlforajaxcall, $urloption, $conf->global->$confkeyforautocompletemode, 0, array());
			$out.= '<style type="text/css">.ui-autocomplete { z-index: 250; }</style>';
			if ($placeholder) $placeholder=' placeholder="'.$placeholder.'"';
			$out.= '<input type="text" class="'.$morecss.'" name="search_'.$htmlname.'" id="search_'.$htmlname.'" value="'.$preselectedvalue.'"'.$placeholder.' />';
		}
		else
		{
			// Immediate load of all database
			$out.=$this->selectForFormsListMateriel($objecttmp, $htmlname, $preselectedvalue, $showempty, $searchkey, $placeholder, $morecss, $moreparams, $forcecombo,0,$isClient,$isFournisseur);
		}

		return $out;
	}

	/**
	 * Output html form to select an object.
	 * Note, this function is called by selectForForms or by ajax selectobject.php
	 *
	 * @param 	Object			$objecttmp			Object
	 * @param	string			$htmlname			Name of HTML select component
	 * @param	int				$preselectedvalue	Preselected value (ID of element)
	 * @param	string			$showempty			''=empty values not allowed, 'string'=value show if we allow empty values (for example 'All', ...)
	 * @param	string			$searchkey			Search value
	 * @param	string			$placeholder		Place holder
	 * @param	string			$morecss			More CSS
	 * @param	string			$moreparams			More params provided to ajax call
	 * @param	int				$forcecombo			Force to load all values and output a standard combobox (with no beautification)
	 * @param	int				$outputmode			0=HTML select string, 1=Array
	 * @return	string								Return HTML string
	 * @see selectForForms()
	 */
    public function selectForFormsListMateriel($objecttmp, $htmlname, $preselectedvalue, $showempty = '', $searchkey = '', $placeholder = '', $morecss = '', $moreparams = '', $forcecombo = 0, $outputmode = 0, $isClient,$isFournisseur)
	{
		global $conf, $langs, $user;

		$prefixforautocompletemode=$objecttmp->element;
		if ($prefixforautocompletemode == 'societe') $prefixforautocompletemode='company';
		$confkeyforautocompletemode=strtoupper($prefixforautocompletemode).'_USE_SEARCH_TO_SELECT';	// For example COMPANY_USE_SEARCH_TO_SELECT

		$fieldstoshow='t.ref';
		if (! empty($objecttmp->fields))	// For object that declare it, it is better to use declared fields ( like societe, contact, ...)
		{
			$tmpfieldstoshow='';
			foreach($objecttmp->fields as $key => $val)
			{
				if ($val['showoncombobox']) $tmpfieldstoshow.=($tmpfieldstoshow?',':'').'t.'.$key;
			}
			if ($tmpfieldstoshow) $fieldstoshow = $tmpfieldstoshow;
		}

		$out='';
		$outarray=array();

		$num=0;

		// Search data
		$sql = "SELECT t.rowid, ".$fieldstoshow." FROM ".MAIN_DB_PREFIX .$objecttmp->table_element." as t";
		if ($objecttmp->ismultientitymanaged == 2)
			if (!$user->rights->societe->client->voir && !$user->societe_id) $sql .= ", ".MAIN_DB_PREFIX."societe_commerciaux as sc";
		//Modif
		if($isClient)
			$sql .= ' WHERE client <> 0';
        elseif($isFournisseur)
			$sql .= ' WHERE fournisseur = 1';
		else
			$sql .= ' WHERE 1=1';
		if(! empty($objecttmp->ismultientitymanaged)) $sql.= " AND t.entity IN (".getEntity($objecttmp->table_element).")";
		if ($objecttmp->ismultientitymanaged == 1 && ! empty($user->societe_id))
		{
			if ($objecttmp->element == 'societe') $sql.= " AND t.rowid = ".$user->societe_id;
				else $sql.= " AND t.fk_soc = ".$user->societe_id;
		}
		if ($searchkey != '') $sql.=natural_search(explode(',', $fieldstoshow), $searchkey);
		if ($objecttmp->ismultientitymanaged == 2)
			if (!$user->rights->societe->client->voir && !$user->societe_id) $sql.= " AND t.rowid = sc.fk_soc AND sc.fk_user = " .$user->id;
		$sql.=$this->db->order($fieldstoshow, "ASC");
		//$sql.=$this->db->plimit($limit, 0);

		// Build output string
		
		$resql=$this->db->query($sql);
		if ($resql)
		{
			if (! $forcecombo)
			{
				include_once DOL_DOCUMENT_ROOT . '/core/lib/ajax.lib.php';
				$out .= ajax_combobox($htmlname, null, $conf->global->$confkeyforautocompletemode);
			}

			// Construct $out and $outarray
			$out.= '<select id="'.$htmlname.'" class="flat'.($morecss?' '.$morecss:'').'"'.($moreparams?' '.$moreparams:'').' name="'.$htmlname.'">'."\n";

			// Warning: Do not use textifempty = ' ' or '&nbsp;' here, or search on key will search on ' key'. Seems it is no more true with selec2 v4
			$textifempty='&nbsp;';

			//if (! empty($conf->use_javascript_ajax) || $forcecombo) $textifempty='';
			if (! empty($conf->global->$confkeyforautocompletemode))
			{
				if ($showempty && ! is_numeric($showempty)) $textifempty=$langs->trans($showempty);
				else $textifempty.=$langs->trans("All");
			}
			if ($showempty) $out.= '<option value="-1">'.$textifempty.'</option>'."\n";

			$num = $this->db->num_rows($resql);
			$i = 0;
			if ($num)
			{
				while ($i < $num)
				{
					$obj = $this->db->fetch_object($resql);
					$label='';
					$tmparray=explode(',', $fieldstoshow);
					foreach($tmparray as $key => $val)
					{
						$val = preg_replace('/t\./', '', $val);
						$label .= (($label && $obj->$val)?' - ':'').$obj->$val;
					}
					if (empty($outputmode))
					{
						if ($preselectedvalue > 0 && $preselectedvalue == $obj->rowid)
						{
							$out.= '<option value="'.$obj->rowid.'" selected>'.$label.'</option>';
						}
						else
						{
							$out.= '<option value="'.$obj->rowid.'">'.$label.'</option>';
						}
					}
					else
					{
						array_push($outarray, array('key'=>$obj->rowid, 'value'=>$label, 'label'=>$label));
					}

					$i++;
					if (($i % 10) == 0) $out.="\n";
				}
			}

			$out.= '</select>'."\n";
		}
		else
		{
			dol_print_error($this->db);
		}

		$this->result=array('nbofelement'=>$num);

		if ($outputmode) return $outarray;
		return $out;
	}

	
}


