<?php
/* Copyright (C) 2017  Laurent Destailleur <eldy@users.sourceforge.net>
 * Copyright (C) ---Put here your own copyright and developer email---
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * \file        class/groupemateriels.class.php
 * \ingroup     materielclient
 * \brief       This file is a CRUD class file for GroupeMateriels (Create/Read/Update/Delete)
 */

// Put here all includes required by your class file
require_once DOL_DOCUMENT_ROOT . '/core/class/commonobject.class.php';
//require_once DOL_DOCUMENT_ROOT . '/societe/class/societe.class.php';
//require_once DOL_DOCUMENT_ROOT . '/product/class/product.class.php';

/**
 * Class for GroupeMateriels
 */
class GroupeMateriels extends CommonObject
{
	/**
	 * @var string ID to identify managed object
	 */
	public $element = 'groupemateriels';

	/**
	 * @var string Name of table without prefix where object is stored
	 */
	public $table_element = 'materielclient_groupemateriels';

	/**
	 * @var int  Does groupemateriels support multicompany module ? 0=No test on entity, 1=Test with field entity, 2=Test with link by societe
	 */
	public $ismultientitymanaged = 0;

	/**
	 * @var int  Does groupemateriels support extrafields ? 0=No, 1=Yes
	 */
	public $isextrafieldmanaged = 1;

	/**
	 * @var string String with name of icon for groupemateriels. Must be the part after the 'object_' into object_groupemateriels.png
	 */
	public $picto = 'groupemateriels@materielclient';


	const STATUS_DRAFT = 0;
	const STATUS_VALIDATED = 1;
	const STATUS_CANCELED = 9;


	/**
	 *  'type' if the field format ('integer', 'integer:Class:pathtoclass', 'varchar(x)', 'double(24,8)', 'text', 'html', 'datetime', 'timestamp', 'float')
	 *  'label' the translation key.
	 *  'enabled' is a condition when the field must be managed.
	 *  'visible' says if field is visible in list (Examples: 0=Not visible, 1=Visible on list and create/update/view forms, 2=Visible on list only, 3=Visible on create/update/view form only (not list), 4=Visible on list and update/view form only (not create). Using a negative value means field is not shown by default on list but can be selected for viewing)
	 *  'noteditable' says if field is not editable (1 or 0)
	 *  'notnull' is set to 1 if not null in database. Set to -1 if we must set data to null if empty ('' or 0).
	 *  'default' is a default value for creation (can still be replaced by the global setup of default values)
	 *  'index' if we want an index in database.
	 *  'foreignkey'=>'tablename.field' if the field is a foreign key (it is recommanded to name the field fk_...).
	 *  'position' is the sort order of field.
	 *  'searchall' is 1 if we want to search in this field when making a search from the quick search button.
	 *  'isameasure' must be set to 1 if you want to have a total on list for this field. Field type must be summable like integer or double(24,8).
	 *  'css' is the CSS style to use on field. For example: 'maxwidth200'
	 *  'help' is a string visible as a tooltip on field
	 *  'comment' is not used. You can store here any text of your choice. It is not used by application.
	 *  'showoncombobox' if value of the field must be visible into the label of the combobox that list record
	 *  'arraykeyval' to set list of value if type is a list of predefined values. For example: array("0"=>"Draft","1"=>"Active","-1"=>"Cancel")
	 */

	// BEGIN MODULEBUILDER PROPERTIES
	/**
	 * @var array  Array with all fields and their property. Do not use it as a static var. It may be modified by constructor.
	 */
	public $fields=array(
		'rowid' => array('type'=>'integer', 'label'=>'TechnicalID', 'enabled'=>1, 'visible'=>-1, 'position'=>1, 'notnull'=>1, 'index'=>1, 'comment'=>"Id",),
		'date_creation' => array('type'=>'datetime', 'label'=>'DateCreation', 'enabled'=>1, 'visible'=>-2, 'position'=>500, 'notnull'=>1,),
		'tms' => array('type'=>'timestamp', 'label'=>'DateModification', 'enabled'=>1, 'visible'=>-2, 'position'=>501, 'notnull'=>-1,),
		'fk_user_creat' => array('type'=>'integer', 'label'=>'UserAuthor', 'enabled'=>1, 'visible'=>-2, 'position'=>510, 'notnull'=>1, 'foreignkey'=>'user.rowid',),
		'fk_user_modif' => array('type'=>'integer', 'label'=>'UserModif', 'enabled'=>1, 'visible'=>-2, 'position'=>511, 'notnull'=>-1,),
		'import_key' => array('type'=>'varchar(14)', 'label'=>'ImportId', 'enabled'=>1, 'visible'=>-2, 'position'=>1000, 'notnull'=>-1,),
		'fk_materiel1' => array('type'=>'integer:Materiel:custom/materielclient/class/materiel.class.php', 'label'=>'Exterieur', 'enabled'=>1, 'visible'=>1, 'position'=>2, 'notnull'=>-1, 'index'=>1, 'comment'=>"Identifiant du groupe exterieur",),
		'fk_materiel2' => array('type'=>'integer:Materiel:custom/materielclient/class/materiel.class.php', 'label'=>'Interieur', 'enabled'=>1, 'visible'=>1, 'position'=>3, 'notnull'=>-1, 'index'=>1, 'comment'=>"Identifiant du groupe interieur",),
		'fk_materiel3' => array('type'=>'integer:Materiel:custom/materielclient/class/materiel.class.php', 'label'=>'Interieur', 'enabled'=>1, 'visible'=>1, 'position'=>4, 'notnull'=>-1, 'comment'=>"Identifiant du groupe interieur",),
		'fk_materiel4' => array('type'=>'integer:Materiel:custom/materielclient/class/materiel.class.php', 'label'=>'Interieur', 'enabled'=>1, 'visible'=>1, 'position'=>5, 'notnull'=>-1, 'comment'=>"Identifiant du groupe interieur",),
		'fk_materiel5' => array('type'=>'integer:Materiel:custom/materielclient/class/materiel.class.php', 'label'=>'Interieur', 'enabled'=>1, 'visible'=>1, 'position'=>6, 'notnull'=>-1, 'comment'=>"Identifiant du groupe interieur",),
		'fk_materiel6' => array('type'=>'integer:Materiel:custom/materielclient/class/materiel.class.php', 'label'=>'Interieur', 'enabled'=>1, 'visible'=>1, 'position'=>7, 'notnull'=>-1, 'comment'=>"Identifiant du groupe interieur",),
		'fk_materiel7' => array('type'=>'integer:Materiel:custom/materielclient/class/materiel.class.php', 'label'=>'Interieur', 'enabled'=>1, 'visible'=>1, 'position'=>8, 'notnull'=>-1, 'comment'=>"Identifiant du groupe interieur",),
		'fk_materiel8' => array('type'=>'integer:Materiel:custom/materielclient/class/materiel.class.php', 'label'=>'Interieur', 'enabled'=>1, 'visible'=>1, 'position'=>9, 'notnull'=>-1, 'comment'=>"Identifiant du groupe interieur",),
		'ref' => array('type'=>'varchar(128)', 'label'=>'NomGroupe', 'enabled'=>1, 'visible'=>1, 'position'=>1, 'notnull'=>1, 'comment'=>"Nom du groupe",),
		'fk_materiel9' => array('type'=>'integer:Materiel:custom/materielclient/class/materiel.class.php', 'label'=>'Interieur', 'enabled'=>1, 'visible'=>1, 'position'=>10, 'notnull'=>-1, 'comment'=>"Identifiant du groupe interieur",),
	);
	public $rowid;
	public $date_creation;
	public $tms;
	public $fk_user_creat;
	public $fk_user_modif;
	public $import_key;
	public $fk_materiel1;
	public $fk_materiel2;
	public $fk_materiel3;
	public $fk_materiel4;
	public $fk_materiel5;
	public $fk_materiel6;
	public $fk_materiel7;
	public $fk_materiel8;
	public $ref;
	public $fk_materiel9;
	// END MODULEBUILDER PROPERTIES


	// If this object has a subtable with lines

	/**
	 * @var int    Name of subtable line
	 */
	//public $table_element_line = 'materielclient_groupematerielsline';

	/**
	 * @var int    Field with ID of parent key if this field has a parent
	 */
	//public $fk_element = 'fk_groupemateriels';

	/**
	 * @var int    Name of subtable class that manage subtable lines
	 */
	//public $class_element_line = 'GroupeMaterielsline';

	/**
	 * @var array	List of child tables. To test if we can delete object.
	 */
	//protected $childtables=array();

	/**
	 * @var array	List of child tables. To know object to delete on cascade.
	 */
	//protected $childtablesoncascade=array('materielclient_groupematerielsdet');

	/**
	 * @var GroupeMaterielsLine[]     Array of subtable lines
	 */
	//public $lines = array();



	/**
	 * Constructor
	 *
	 * @param DoliDb $db Database handler
	 */
	public function __construct(DoliDB $db)
	{
		global $conf, $langs;

		$this->db = $db;

		if (empty($conf->global->MAIN_SHOW_TECHNICAL_ID) && isset($this->fields['rowid'])) $this->fields['rowid']['visible']=0;
		if (empty($conf->multicompany->enabled) && isset($this->fields['entity'])) $this->fields['entity']['enabled']=0;

		// Unset fields that are disabled
		foreach($this->fields as $key => $val)
		{
			if (isset($val['enabled']) && empty($val['enabled']))
			{
				unset($this->fields[$key]);
			}
		}

		// Translate some data of arrayofkeyval
		foreach($this->fields as $key => $val)
		{
			if (is_array($val['arrayofkeyval']))
			{
				foreach($val['arrayofkeyval'] as $key2 => $val2)
				{
					$this->fields[$key]['arrayofkeyval'][$key2]=$langs->trans($val2);
				}
			}
		}
	}

	/**
	 * Create object into database
	 *
	 * @param  User $user      User that creates
	 * @param  bool $notrigger false=launch triggers after, true=disable triggers
	 * @return int             <0 if KO, Id of created object if OK
	 */
	public function create(User $user, $notrigger = false)
	{
		return $this->createCommon($user, $notrigger);
	}

	/**
	 * Clone an object into another one
	 *
	 * @param  	User 	$user      	User that creates
	 * @param  	int 	$fromid     Id of object to clone
	 * @return 	mixed 				New object created, <0 if KO
	 */
	public function createFromClone(User $user, $fromid)
	{
		global $langs, $extrafields;
	    $error = 0;

	    dol_syslog(__METHOD__, LOG_DEBUG);

	    $object = new self($this->db);

	    $this->db->begin();

	    // Load source object
	    $result = $object->fetchCommon($fromid);
	    if ($result > 0 && ! empty($object->table_element_line)) $object->fetchLines();

	    // get lines so they will be clone
	    //foreach($this->lines as $line)
	    //	$line->fetch_optionals();

	    // Reset some properties
	    unset($object->id);
	    unset($object->fk_user_creat);
	    unset($object->import_key);


	    // Clear fields
	    $object->ref = "copy_of_".$object->ref;
	    $object->title = $langs->trans("CopyOf")." ".$object->title;
	    // ...
	    // Clear extrafields that are unique
	    if (is_array($object->array_options) && count($object->array_options) > 0)
	    {
	    	$extrafields->fetch_name_optionals_label($this->element);
	    	foreach($object->array_options as $key => $option)
	    	{
	    		$shortkey = preg_replace('/options_/', '', $key);
	    		if (! empty($extrafields->attributes[$this->element]['unique'][$shortkey]))
	    		{
	    			//var_dump($key); var_dump($clonedObj->array_options[$key]); exit;
	    			unset($object->array_options[$key]);
	    		}
	    	}
	    }

	    // Create clone
		$object->context['createfromclone'] = 'createfromclone';
	    $result = $object->createCommon($user);
	    if ($result < 0) {
	        $error++;
	        $this->error = $object->error;
	        $this->errors = $object->errors;
	    }

	    if (! $error)
	    {
	    	// copy internal contacts
	    	if ($this->copy_linked_contact($object, 'internal') < 0)
	    	{
	    		$error++;
	    	}
	    }

	    if (! $error)
	    {
	    	// copy external contacts if same company
	    	if (property_exists($this, 'socid') && $this->socid == $object->socid)
	    	{
	    		if ($this->copy_linked_contact($object, 'external') < 0)
	    			$error++;
	    	}
	    }

	    unset($object->context['createfromclone']);

	    // End
	    if (!$error) {
	        $this->db->commit();
	        return $object;
	    } else {
	        $this->db->rollback();
	        return -1;
	    }
	}

	/**
	 * Load object in memory from the database
	 *
	 * @param int    $id   Id object
	 * @param string $ref  Ref
	 * @return int         <0 if KO, 0 if not found, >0 if OK
	 */
	public function fetch($id, $ref = null)
	{
		$result = $this->fetchCommon($id, $ref);
		if ($result > 0 && ! empty($this->table_element_line)) $this->fetchLines();
		return $result;
	}

	/**
	 * Load object lines in memory from the database
	 *
	 * @return int         <0 if KO, 0 if not found, >0 if OK
	 */
	public function fetchLines()
	{
		$this->lines=array();

		$result = $this->fetchLinesCommon();
		return $result;
	}


	/**
	 * Load list of objects in memory from the database.
	 *
	 * @param  string      $sortorder    Sort Order
	 * @param  string      $sortfield    Sort field
	 * @param  int         $limit        limit
	 * @param  int         $offset       Offset
	 * @param  array       $filter       Filter array. Example array('field'=>'valueforlike', 'customurl'=>...)
	 * @param  string      $filtermode   Filter mode (AND or OR)
	 * @return array|int                 int <0 if KO, array of pages if OK
	 */
	public function fetchAll($sortorder = '', $sortfield = '', $limit = 0, $offset = 0, array $filter = array(), $filtermode = 'AND')
	{
		global $conf;

		dol_syslog(__METHOD__, LOG_DEBUG);

		$records=array();

		$sql = 'SELECT ';
		$sql .= $this->getFieldList();
		$sql .= ' FROM ' . MAIN_DB_PREFIX . $this->table_element. ' as t';
		if (isset($this->ismultientitymanaged) && $this->ismultientitymanaged == 1) $sql .= ' WHERE t.entity IN ('.getEntity($this->table_element).')';
		else $sql .= ' WHERE 1 = 1';
		// Manage filter
		$sqlwhere = array();
		if (count($filter) > 0) {
			foreach ($filter as $key => $value) {
				if ($key=='t.rowid') {
					$sqlwhere[] = $key . '='. $value;
				}
				elseif (strpos($key, 'date') !== false) {
					$sqlwhere[] = $key.' = \''.$this->db->idate($value).'\'';
				}
				elseif ($key=='customsql') {
					$sqlwhere[] = $value;
				}
				else {
					$sqlwhere[] = $key . ' LIKE \'%' . $this->db->escape($value) . '%\'';
				}
			}
		}
		if (count($sqlwhere) > 0) {
			$sql .= ' AND (' . implode(' '.$filtermode.' ', $sqlwhere).')';
		}

		if (!empty($sortfield)) {
			$sql .= $this->db->order($sortfield, $sortorder);
		}
		if (!empty($limit)) {
			$sql .=  ' ' . $this->db->plimit($limit, $offset);
		}
		
		$resql = $this->db->query($sql);
		if ($resql) {
			$num = $this->db->num_rows($resql);
            $i = 0;
			while ($i < min($limit, $num))
			{
			    $obj = $this->db->fetch_object($resql);

				$record = new self($this->db);
				$record->setVarsFromFetchObj($obj);

				$records[$record->id] = $record;

				$i++;
			}
			$this->db->free($resql);

			return $records;
		} else {
			$this->errors[] = 'Error ' . $this->db->lasterror();
			dol_syslog(__METHOD__ . ' ' . join(',', $this->errors), LOG_ERR);

			return -1;
		}
	}

	/**
	 * Update object into database
	 *
	 * @param  User $user      User that modifies
	 * @param  bool $notrigger false=launch triggers after, true=disable triggers
	 * @return int             <0 if KO, >0 if OK
	 */
	public function update(User $user, $notrigger = false)
	{
		return $this->updateCommon($user, $notrigger);
	}

	/**
	 * Delete object in database
	 *
	 * @param User $user       User that deletes
	 * @param bool $notrigger  false=launch triggers after, true=disable triggers
	 * @return int             <0 if KO, >0 if OK
	 */
	public function delete(User $user, $notrigger = false)
	{
		return $this->deleteCommon($user, $notrigger);
		//return $this->deleteCommon($user, $notrigger, 1);
	}

	/**
	 *  Delete a line of object in database
	 *
	 *	@param  User	$user       User that delete
	 *  @param	int		$idline		Id of line to delete
	 *  @param 	bool 	$notrigger  false=launch triggers after, true=disable triggers
	 *  @return int         		>0 if OK, <0 if KO
	 */
	public function deleteLine(User $user, $idline, $notrigger = false)
	{
		if ($this->status < 0)
		{
			$this->error = 'ErrorDeleteLineNotAllowedByObjectStatus';
			return -2;
		}

		return $this->deleteLineCommon($user, $idline, $notrigger);
	}

    /**
     *  Return a link to the object card (with optionaly the picto)
     *
     *  @param  int     $withpicto                  Include picto in link (0=No picto, 1=Include picto into link, 2=Only picto)
     *  @param  string  $option                     On what the link point to ('nolink', ...)
     *  @param  int     $notooltip                  1=Disable tooltip
     *  @param  string  $morecss                    Add more css on link
     *  @param  int     $save_lastsearch_value      -1=Auto, 0=No save of lastsearch_values when clicking, 1=Save lastsearch_values whenclicking
     *  @return	string                              String with URL
     */
    public function getNomUrl($withpicto = 0, $option = '', $notooltip = 0, $morecss = '', $save_lastsearch_value = -1)
    {
        global $conf, $langs, $hookmanager;

        if (! empty($conf->dol_no_mouse_hover)) $notooltip=1;   // Force disable tooltips

        $result = '';

        $label = '<u>' . $langs->trans("GroupeMateriels") . '</u>';
        $label.= '<br>';
        $label.= '<b>' . $langs->trans('Ref') . ':</b> ' . $this->ref;

        $url = dol_buildpath('/materielclient/groupemateriels_card.php', 1).'?id='.$this->id;

        if ($option != 'nolink')
        {
            // Add param to save lastsearch_values or not
            $add_save_lastsearch_values=($save_lastsearch_value == 1 ? 1 : 0);
            if ($save_lastsearch_value == -1 && preg_match('/list\.php/', $_SERVER["PHP_SELF"])) $add_save_lastsearch_values=1;
            if ($add_save_lastsearch_values) $url.='&save_lastsearch_values=1';
        }

        $linkclose='';
        if (empty($notooltip))
        {
            if (! empty($conf->global->MAIN_OPTIMIZEFORTEXTBROWSER))
            {
                $label=$langs->trans("ShowGroupeMateriels");
                $linkclose.=' alt="'.dol_escape_htmltag($label, 1).'"';
            }
            $linkclose.=' title="'.dol_escape_htmltag($label, 1).'"';
            $linkclose.=' class="classfortooltip'.($morecss?' '.$morecss:'').'"';

            /*
             $hookmanager->initHooks(array('groupematerielsdao'));
             $parameters=array('id'=>$this->id);
             $reshook=$hookmanager->executeHooks('getnomurltooltip',$parameters,$this,$action);    // Note that $action and $object may have been modified by some hooks
             if ($reshook > 0) $linkclose = $hookmanager->resPrint;
             */
        }
        else $linkclose = ($morecss?' class="'.$morecss.'"':'');

		$linkstart = '<a href="'.$url.'"';
		$linkstart.=$linkclose.'>';
		$linkend='</a>';

		$result .= $linkstart;
		if ($withpicto) $result.=img_object(($notooltip?'':$label), ($this->picto?$this->picto:'generic'), ($notooltip?(($withpicto != 2) ? 'class="paddingright"' : ''):'class="'.(($withpicto != 2) ? 'paddingright ' : '').'classfortooltip"'), 0, 0, $notooltip?0:1);
		if ($withpicto != 2) $result.= $this->ref;
		$result .= $linkend;
		//if ($withpicto != 2) $result.=(($addlabel && $this->label) ? $sep . dol_trunc($this->label, ($addlabel > 1 ? $addlabel : 0)) : '');

		global $action,$hookmanager;
		$hookmanager->initHooks(array('groupematerielsdao'));
		$parameters=array('id'=>$this->id, 'getnomurl'=>$result);
		$reshook=$hookmanager->executeHooks('getNomUrl', $parameters, $this, $action);    // Note that $action and $object may have been modified by some hooks
		if ($reshook > 0) $result = $hookmanager->resPrint;
		else $result .= $hookmanager->resPrint;

		return $result;
    }

	/**
	 *  Return label of the status
	 *
	 *  @param  int		$mode          0=long label, 1=short label, 2=Picto + short label, 3=Picto, 4=Picto + long label, 5=Short label + Picto, 6=Long label + Picto
	 *  @return	string 			       Label of status
	 */
	public function getLibStatut($mode = 0)
	{
		return $this->LibStatut($this->status, $mode);
	}

    // phpcs:disable PEAR.NamingConventions.ValidFunctionName.ScopeNotCamelCaps
	/**
	 *  Return the status
	 *
	 *  @param	int		$status        Id status
	 *  @param  int		$mode          0=long label, 1=short label, 2=Picto + short label, 3=Picto, 4=Picto + long label, 5=Short label + Picto, 6=Long label + Picto
	 *  @return string 			       Label of status
	 */
	public function LibStatut($status, $mode = 0)
	{
		// phpcs:enable
		if (empty($this->labelstatus))
		{
			global $langs;
			//$langs->load("materielclient");
			$this->labelstatus[self::STATUS_DRAFT] = $langs->trans('Draft');
			$this->labelstatus[self::STATUS_VALIDATED] = $langs->trans('Enabled');
			$this->labelstatus[self::STATUS_CANCELED] = $langs->trans('Disabled');
		}

		if ($mode == 0)
		{
			return $this->labelstatus[$status];
		}
		elseif ($mode == 1)
		{
			return $this->labelstatus[$status];
		}
		elseif ($mode == 2)
		{
			return img_picto($this->labelstatus[$status], 'statut'.$status, '', false, 0, 0, '', 'valignmiddle').' '.$this->labelstatus[$status];
		}
		elseif ($mode == 3)
		{
			return img_picto($this->labelstatus[$status], 'statut'.$status, '', false, 0, 0, '', 'valignmiddle');
		}
		elseif ($mode == 4)
		{
			return img_picto($this->labelstatus[$status], 'statut'.$status, '', false, 0, 0, '', 'valignmiddle').' '.$this->labelstatus[$status];
		}
		elseif ($mode == 5)
		{
			return $this->labelstatus[$status].' '.img_picto($this->labelstatus[$status], 'statut'.$status, '', false, 0, 0, '', 'valignmiddle');
		}
		elseif ($mode == 6)
		{
			return $this->labelstatus[$status].' '.img_picto($this->labelstatus[$status], 'statut'.$status, '', false, 0, 0, '', 'valignmiddle');
		}
	}

	/**
	 *	Load the info information in the object
	 *
	 *	@param  int		$id       Id of object
	 *	@return	void
	 */
	public function info($id)
	{
		$sql = 'SELECT rowid, date_creation as datec, tms as datem,';
		$sql.= ' fk_user_creat, fk_user_modif';
		$sql.= ' FROM '.MAIN_DB_PREFIX.$this->table_element.' as t';
		$sql.= ' WHERE t.rowid = '.$id;
		
		$result=$this->db->query($sql);
		if ($result)
		{
			if ($this->db->num_rows($result))
			{
				$obj = $this->db->fetch_object($result);
				$this->id = $obj->rowid;
				if ($obj->fk_user_author)
				{
					$cuser = new User($this->db);
					$cuser->fetch($obj->fk_user_author);
					$this->user_creation   = $cuser;
				}

				if ($obj->fk_user_valid)
				{
					$vuser = new User($this->db);
					$vuser->fetch($obj->fk_user_valid);
					$this->user_validation = $vuser;
				}

				if ($obj->fk_user_cloture)
				{
					$cluser = new User($this->db);
					$cluser->fetch($obj->fk_user_cloture);
					$this->user_cloture   = $cluser;
				}

				$this->date_creation     = $this->db->jdate($obj->datec);
				$this->date_modification = $this->db->jdate($obj->datem);
				$this->date_validation   = $this->db->jdate($obj->datev);
			}

			$this->db->free($result);
		}
		else
		{
			dol_print_error($this->db);
		}
	}

	/**
	 * Initialise object with example values
	 * Id must be 0 if object instance is a specimen
	 *
	 * @return void
	 */
	public function initAsSpecimen()
	{
		$this->initAsSpecimenCommon();
	}

	/**
	 * 	Create an array of lines
	 *
	 * 	@return array|int		array of lines if OK, <0 if KO
	 */
	public function getLinesArray()
	{
	    $this->lines=array();

	    $objectline = new GroupeMaterielsLine($this->db);
	    $result = $objectline->fetchAll('ASC', 'position', 0, 0, array('customsql'=>'fk_groupemateriels = '.$this->id));

	    if (is_numeric($result))
	    {
	        $this->error = $this->error;
	        $this->errors = $this->errors;
	        return $result;
	    }
	    else
	    {
	        $this->lines = $result;
	        return $this->lines;
	    }
	}

	/**
	 *  Create a document onto disk according to template module.
	 *
	 *  @param	    string		$modele			Force template to use ('' to not force)
	 *  @param		Translate	$outputlangs	objet lang a utiliser pour traduction
	 *  @param      int			$hidedetails    Hide details of lines
	 *  @param      int			$hidedesc       Hide description
	 *  @param      int			$hideref        Hide ref
	 *  @param      null|array  $moreparams     Array to provide more information
	 *  @return     int         				0 if KO, 1 if OK
	 */
	public function generateDocument($modele, $outputlangs, $hidedetails = 0, $hidedesc = 0, $hideref = 0, $moreparams = null)
	{
		global $conf,$langs;

		$langs->load("materielclient@materielclient");

		if (! dol_strlen($modele)) {

			$modele = 'standard';

			if ($this->modelpdf) {
				$modele = $this->modelpdf;
			} elseif (! empty($conf->global->GROUPEMATERIELS_ADDON_PDF)) {
				$modele = $conf->global->GROUPEMATERIELS_ADDON_PDF;
			}
		}

		$modelpath = "core/modules/materielclient/doc/";

		return $this->commonGenerateDocument($modelpath, $modele, $outputlangs, $hidedetails, $hidedesc, $hideref, $moreparams);
	}

	/**
	 * Action executed by scheduler
	 * CAN BE A CRON TASK. In such a case, parameters come from the schedule job setup field 'Parameters'
	 *
	 * @return	int			0 if OK, <>0 if KO (this function is used also by cron so only 0 is OK)
	 */
	//public function doScheduledJob($param1, $param2, ...)
	public function doScheduledJob()
	{
		global $conf, $langs;

		//$conf->global->SYSLOG_FILE = 'DOL_DATA_ROOT/dolibarr_mydedicatedlofile.log';

		$error = 0;
		$this->output = '';
		$this->error='';

		dol_syslog(__METHOD__, LOG_DEBUG);

		$now = dol_now();

		$this->db->begin();

		// ...

		$this->db->commit();

		return $error;
	}

	/**
	 * Return HTML string to put an input field into a page
	 * Code very similar with showInputField of extra fields
	 *
	 * @param  array   		$val	       Array of properties for field to show
	 * @param  string  		$key           Key of attribute
	 * @param  string  		$value         Preselected value to show (for date type it must be in timestamp format, for amount or price it must be a php numeric value)
	 * @param  string  		$moreparam     To add more parameters on html input tag
	 * @param  string  		$keysuffix     Prefix string to add into name and id of field (can be used to avoid duplicate names)
	 * @param  string  		$keyprefix     Suffix string to add into name and id of field (can be used to avoid duplicate names)
	 * @param  string|int	$morecss       Value for css to define style/length of field. May also be a numeric.
	 * @return string
	 */
	
	public function showInputFieldGroupe($val, $key, $value, $moreparam = '', $keysuffix = '', $keyprefix = '', $morecss = 0, $idtiers ,$isExterieur)
	{

		
		global $conf,$langs,$form;

		if (! is_object($form))
		{
			
			require_once DOL_DOCUMENT_ROOT.'/core/class/html.form.class.php';
			$form=new Form($this->db);
		}

		$val=$this->fields[$key];

		$out='';
        $type='';
        $param = array();
        $param['options']=array();
        $size =$this->fields[$key]['size'];
        // Because we work on extrafields
        if(preg_match('/^integer:(.*):(.*)/i', $val['type'], $reg)){
            $param['options']=array($reg[1].':'.$reg[2]=>'N');
            $type ='link';
        } elseif(preg_match('/^link:(.*):(.*)/i', $val['type'], $reg)) {
            $param['options']=array($reg[1].':'.$reg[2]=>'N');
            $type ='link';
        } elseif(preg_match('/^sellist:(.*):(.*):(.*):(.*)/i', $val['type'], $reg)) {
            $param['options']=array($reg[1].':'.$reg[2].':'.$reg[3].':'.$reg[4]=>'N');
            $type ='sellist';
        } elseif(preg_match('/varchar\((\d+)\)/', $val['type'], $reg)) {
            $param['options']=array();
            $type ='varchar';
            $size=$reg[1];
        } elseif(preg_match('/varchar/', $val['type'])) {
            $param['options']=array();
            $type ='varchar';
        } elseif(is_array($this->fields[$key]['arrayofkeyval'])) {
            $param['options']=$this->fields[$key]['arrayofkeyval'];
            $type ='select';
        } else {
            $param['options']=array();
            $type =$this->fields[$key]['type'];
        }

		$label=$this->fields[$key]['label'];
		//$elementtype=$this->fields[$key]['elementtype'];	// Seems not used
		$default=$this->fields[$key]['default'];
		$computed=$this->fields[$key]['computed'];
		$unique=$this->fields[$key]['unique'];
		$required=$this->fields[$key]['required'];

		$langfile=$this->fields[$key]['langfile'];
		$list=$this->fields[$key]['list'];
		$hidden=(in_array(abs($this->fields[$key]['visible']), array(0,2)) ? 1 : 0);

		$objectid = $this->id;


		if ($computed)
		{
			if (! preg_match('/^search_/', $keyprefix)) return '<span class="opacitymedium">'.$langs->trans("AutomaticallyCalculated").'</span>';
			else return '';
		}


		// Set value of $morecss. For this, we use in priority showsize from parameters, then $val['css'] then autodefine
		if (empty($morecss) && ! empty($val['css']))
		{
		    $morecss = $val['css'];
		}
		elseif (empty($morecss))
		{
			if ($type == 'date')
			{
				$morecss = 'minwidth100imp';
			}
			elseif ($type == 'datetime')
			{
				$morecss = 'minwidth200imp';
			}
			elseif (in_array($type, array('int','integer','price')) || preg_match('/^double(\([0-9],[0-9]\)){0,1}/', $type))
			{
				$morecss = 'maxwidth75';
			} elseif ($type == 'url') {
				$morecss='minwidth400';
			}
			elseif ($type == 'boolean')
			{
				$morecss='';
			}
			else
			{
				if (round($size) < 12)
				{
					$morecss = 'minwidth100';
				}
				elseif (round($size) <= 48)
				{
					$morecss = 'minwidth200';
				}
				else
				{
					$morecss = 'minwidth400';
				}
			}
		}
		
		if (in_array($type, array('date','datetime')))
		{
			$tmp=explode(',', $size);
			$newsize=$tmp[0];

			$showtime = in_array($type, array('datetime')) ? 1 : 0;

			// Do not show current date when field not required (see selectDate() method)
			if (!$required && $value == '') $value = '-1';

			// TODO Must also support $moreparam
			$out = $form->selectDate($value, $keyprefix.$key.$keysuffix, $showtime, $showtime, $required, '', 1, (($keyprefix != 'search_' && $keyprefix != 'search_options_') ? 1 : 0), 0, 1);
		}
		elseif (in_array($type, array('int','integer')))
		{
			$tmp=explode(',', $size);
			$newsize=$tmp[0];
			$out='<input type="text" class="flat '.$morecss.' maxwidthonsmartphone" name="'.$keyprefix.$key.$keysuffix.'" id="'.$keyprefix.$key.$keysuffix.'" maxlength="'.$newsize.'" value="'.dol_escape_htmltag($value).'"'.($moreparam?$moreparam:'').'>';
		}
		elseif (in_array($type, array('real')))
		{
		    $out='<input type="text" class="flat '.$morecss.' maxwidthonsmartphone" name="'.$keyprefix.$key.$keysuffix.'" id="'.$keyprefix.$key.$keysuffix.'" value="'.dol_escape_htmltag($value).'"'.($moreparam?$moreparam:'').'>';
		}
		elseif (preg_match('/varchar/', $type))
		{
			$out='<input type="text" class="flat '.$morecss.' maxwidthonsmartphone" name="'.$keyprefix.$key.$keysuffix.'" id="'.$keyprefix.$key.$keysuffix.'" maxlength="'.$size.'" value="'.dol_escape_htmltag($value).'"'.($moreparam?$moreparam:'').'>';
		}
		elseif (in_array($type, array('mail', 'phone', 'url')))
		{
			$out='<input type="text" class="flat '.$morecss.' maxwidthonsmartphone" name="'.$keyprefix.$key.$keysuffix.'" id="'.$keyprefix.$key.$keysuffix.'" value="'.dol_escape_htmltag($value).'" '.($moreparam?$moreparam:'').'>';
		}
		elseif ($type == 'text')
		{
			if (! preg_match('/search_/', $keyprefix))		// If keyprefix is search_ or search_options_, we must just use a simple text field
			{
				require_once DOL_DOCUMENT_ROOT.'/core/class/doleditor.class.php';
				$doleditor=new DolEditor($keyprefix.$key.$keysuffix, $value, '', 200, 'dolibarr_notes', 'In', false, false, false, ROWS_5, '90%');
				$out=$doleditor->Create(1);
			}
			else
			{
				$out='<input type="text" class="flat '.$morecss.' maxwidthonsmartphone" name="'.$keyprefix.$key.$keysuffix.'" id="'.$keyprefix.$key.$keysuffix.'" value="'.dol_escape_htmltag($value).'" '.($moreparam?$moreparam:'').'>';
			}
		}
		elseif ($type == 'html')
		{
			if (! preg_match('/search_/', $keyprefix))		// If keyprefix is search_ or search_options_, we must just use a simple text field
			{
				require_once DOL_DOCUMENT_ROOT.'/core/class/doleditor.class.php';
				$doleditor=new DolEditor($keyprefix.$key.$keysuffix, $value, '', 200, 'dolibarr_notes', 'In', false, false, ! empty($conf->fckeditor->enabled) && $conf->global->FCKEDITOR_ENABLE_SOCIETE, ROWS_5, '90%');
				$out=$doleditor->Create(1);
			}
			else
			{
				$out='<input type="text" class="flat '.$morecss.' maxwidthonsmartphone" name="'.$keyprefix.$key.$keysuffix.'" id="'.$keyprefix.$key.$keysuffix.'" value="'.dol_escape_htmltag($value).'" '.($moreparam?$moreparam:'').'>';
			}
		}
		elseif ($type == 'boolean')
		{
			$checked='';
			if (!empty($value)) {
				$checked=' checked value="1" ';
			} else {
				$checked=' value="1" ';
			}
			$out='<input type="checkbox" class="flat '.$morecss.' maxwidthonsmartphone" name="'.$keyprefix.$key.$keysuffix.'" id="'.$keyprefix.$key.$keysuffix.'" '.$checked.' '.($moreparam?$moreparam:'').'>';
		}
		elseif ($type == 'price')
		{
			if (!empty($value)) {		// $value in memory is a php numeric, we format it into user number format.
				$value=price($value);
			}
			$out='<input type="text" class="flat '.$morecss.' maxwidthonsmartphone" name="'.$keyprefix.$key.$keysuffix.'" id="'.$keyprefix.$key.$keysuffix.'" value="'.$value.'" '.($moreparam?$moreparam:'').'> '.$langs->getCurrencySymbol($conf->currency);
		}
		elseif (preg_match('/^double(\([0-9],[0-9]\)){0,1}/', $type))
		{
			if (!empty($value)) {		// $value in memory is a php numeric, we format it into user number format.
				$value=price($value);
			}
			$out='<input type="text" class="flat '.$morecss.' maxwidthonsmartphone" name="'.$keyprefix.$key.$keysuffix.'" id="'.$keyprefix.$key.$keysuffix.'" value="'.$value.'" '.($moreparam?$moreparam:'').'> ';
		}
		elseif ($type == 'select')
		{
			$out = '';
			if (! empty($conf->use_javascript_ajax) && ! empty($conf->global->MAIN_EXTRAFIELDS_USE_SELECT2))
			{
				include_once DOL_DOCUMENT_ROOT . '/core/lib/ajax.lib.php';
				$out.= ajax_combobox($keyprefix.$key.$keysuffix, array(), 0);
			}

			$out.='<select class="flat '.$morecss.' maxwidthonsmartphone" name="'.$keyprefix.$key.$keysuffix.'" id="'.$keyprefix.$key.$keysuffix.'" '.($moreparam?$moreparam:'').'>';
                if((! isset($this->fields[$key]['default'])) ||($this->fields[$key]['notnull']!=1))$out.='<option value="0">&nbsp;</option>';
			foreach ($param['options'] as $key => $val)
			{
				if ((string) $key == '') continue;
				list($val, $parent) = explode('|', $val);
				$out.='<option value="'.$key.'"';
				$out.= (((string) $value == (string) $key)?' selected':'');
				$out.= (!empty($parent)?' parent="'.$parent.'"':'');
				$out.='>'.$val.'</option>';
			}
			$out.='</select>';
		}
		elseif ($type == 'sellist')
		{
			print 'sellist';
			$out = '';
			if (! empty($conf->use_javascript_ajax) && ! empty($conf->global->MAIN_EXTRAFIELDS_USE_SELECT2))
			{
				include_once DOL_DOCUMENT_ROOT . '/core/lib/ajax.lib.php';
				$out.= ajax_combobox($keyprefix.$key.$keysuffix, array(), 0);
			}

			$out.='<select class="flat '.$morecss.' maxwidthonsmartphone" name="'.$keyprefix.$key.$keysuffix.'" id="'.$keyprefix.$key.$keysuffix.'" '.($moreparam?$moreparam:'').'>';
			if (is_array($param['options']))
			{
				$param_list=array_keys($param['options']);
				$InfoFieldList = explode(":", $param_list[0]);
				$parentName='';
				$parentField='';
				// 0 : tableName
				// 1 : label field name
				// 2 : key fields name (if differ of rowid)
				// 3 : key field parent (for dependent lists)
				// 4 : where clause filter on column or table extrafield, syntax field='value' or extra.field=value
				$keyList=(empty($InfoFieldList[2])?'rowid':$InfoFieldList[2].' as rowid');


				if (count($InfoFieldList) > 4 && ! empty($InfoFieldList[4]))
				{
					if (strpos($InfoFieldList[4], 'extra.') !== false)
					{
						$keyList='main.'.$InfoFieldList[2].' as rowid';
					} else {
						$keyList=$InfoFieldList[2].' as rowid';
					}
				}
				if (count($InfoFieldList) > 3 && ! empty($InfoFieldList[3]))
				{
					list($parentName, $parentField) = explode('|', $InfoFieldList[3]);
					$keyList.= ', '.$parentField;
				}

				$fields_label = explode('|', $InfoFieldList[1]);
				if (is_array($fields_label))
				{
					$keyList .=', ';
					$keyList .= implode(', ', $fields_label);
				}

				$sqlwhere='';
				$sql = 'SELECT '.$keyList;
				$sql.= ' FROM '.MAIN_DB_PREFIX .$InfoFieldList[0];
				if (!empty($InfoFieldList[4]))
				{
					// can use SELECT request
					if (strpos($InfoFieldList[4], '$SEL$')!==false) {
						$InfoFieldList[4]=str_replace('$SEL$', 'SELECT', $InfoFieldList[4]);
					}

					// current object id can be use into filter
					if (strpos($InfoFieldList[4], '$ID$')!==false && !empty($objectid)) {
						$InfoFieldList[4]=str_replace('$ID$', $objectid, $InfoFieldList[4]);
					} else {
						$InfoFieldList[4]=str_replace('$ID$', '0', $InfoFieldList[4]);
					}
					//We have to join on extrafield table
					if (strpos($InfoFieldList[4], 'extra')!==false)
					{
						$sql.= ' as main, '.MAIN_DB_PREFIX .$InfoFieldList[0].'_extrafields as extra';
						$sqlwhere.= ' WHERE extra.fk_object=main.'.$InfoFieldList[2]. ' AND '.$InfoFieldList[4];
					}
					else
					{
						$sqlwhere.= ' WHERE '.$InfoFieldList[4];
					}
				}
				else
				{
					
					if($idtiers)
						if($isExterieur)
							$sqlwhere .= 'SELECT m.rowid FROM llx_materielclient_materiel as m JOIN llx_societe as t ON m.fk_soc = t.rowid WHERE t.rowid = '.$idtiers.' AND (m.emplacement IS NULL OR m.emplacement = 0)';
						else
							$sqlwhere .= 'SELECT m.rowid FROM llx_materielclient_materiel as m JOIN llx_societe as t ON m.fk_soc = t.rowid WHERE t.rowid = '.$idtiers.' AND m.emplacement = 1';
					else
					$sqlwhere.= ' WHERE 1=1';
				}
				// Some tables may have field, some other not. For the moment we disable it.
				if (in_array($InfoFieldList[0], array('tablewithentity')))
				{
					$sqlwhere.= ' AND entity = '.$conf->entity;
				}
				$sql.=$sqlwhere;
				

				$sql .= ' ORDER BY ' . implode(', ', $fields_label);

				dol_syslog(get_class($this).'::showInputField type=sellist', LOG_DEBUG);
				
				$resql = $this->db->query($sql);
				if ($resql)
				{
					$out.='<option value="0">&nbsp;</option>';
					$num = $this->db->num_rows($resql);
					$i = 0;
					while ($i < $num)
					{
						$labeltoshow='';
						$obj = $this->db->fetch_object($resql);

						// Several field into label (eq table:code|libelle:rowid)
						$notrans = false;
						$fields_label = explode('|', $InfoFieldList[1]);
						if (is_array($fields_label))
						{
							$notrans = true;
							foreach ($fields_label as $field_toshow)
							{
								$labeltoshow.= $obj->$field_toshow.' ';
							}
						}
						else
						{
							$labeltoshow=$obj->{$InfoFieldList[1]};
						}
						$labeltoshow=dol_trunc($labeltoshow, 45);

						if ($value == $obj->rowid)
						{
							foreach ($fields_label as $field_toshow)
							{
								$translabel=$langs->trans($obj->$field_toshow);
								if ($translabel!=$obj->$field_toshow) {
									$labeltoshow=dol_trunc($translabel, 18).' ';
								}else {
									$labeltoshow=dol_trunc($obj->$field_toshow, 18).' ';
								}
							}
							$out.='<option value="'.$obj->rowid.'" selected>'.$labeltoshow.'</option>';
						}
						else
						{
							if (! $notrans)
							{
								$translabel=$langs->trans($obj->{$InfoFieldList[1]});
								if ($translabel!=$obj->{$InfoFieldList[1]}) {
									$labeltoshow=dol_trunc($translabel, 18);
								}
								else {
									$labeltoshow=dol_trunc($obj->{$InfoFieldList[1]}, 18);
								}
							}
							if (empty($labeltoshow)) $labeltoshow='(not defined)';
							if ($value==$obj->rowid)
							{
								$out.='<option value="'.$obj->rowid.'" selected>'.$labeltoshow.'</option>';
							}

							if (!empty($InfoFieldList[3]) && $parentField)
							{
								$parent = $parentName.':'.$obj->{$parentField};
							}

							$out.='<option value="'.$obj->rowid.'"';
							$out.= ($value==$obj->rowid?' selected':'');
							$out.= (!empty($parent)?' parent="'.$parent.'"':'');
							$out.='>'.$labeltoshow.'</option>';
						}

						$i++;
					}
					$this->db->free($resql);
				}
				else {
					print 'Error in request '.$sql.' '.$this->db->lasterror().'. Check setup of extra parameters.<br>';
				}
			}
			$out.='</select>';
		}
		elseif ($type == 'checkbox')
		{
			$value_arr=explode(',', $value);
			$out=$form->multiselectarray($keyprefix.$key.$keysuffix, (empty($param['options'])?null:$param['options']), $value_arr, '', 0, '', 0, '100%');
		}
		elseif ($type == 'radio')
		{
			$out='';
			foreach ($param['options'] as $keyopt => $val)
			{
				$out.='<input class="flat '.$morecss.'" type="radio" name="'.$keyprefix.$key.$keysuffix.'" id="'.$keyprefix.$key.$keysuffix.'" '.($moreparam?$moreparam:'');
				$out.=' value="'.$keyopt.'"';
				$out.=' id="'.$keyprefix.$key.$keysuffix.'_'.$keyopt.'"';
				$out.= ($value==$keyopt?'checked':'');
				$out.='/><label for="'.$keyprefix.$key.$keysuffix.'_'.$keyopt.'">'.$val.'</label><br>';
			}
		}
		elseif ($type == 'chkbxlst')
		{
			print 'combobox';
			if (is_array($value)) {
				$value_arr = $value;
			}
			else {
				$value_arr = explode(',', $value);
			}

			if (is_array($param['options'])) {
				$param_list = array_keys($param['options']);
				$InfoFieldList = explode(":", $param_list[0]);
				$parentName='';
				$parentField='';
				// 0 : tableName
				// 1 : label field name
				// 2 : key fields name (if differ of rowid)
				// 3 : key field parent (for dependent lists)
				// 4 : where clause filter on column or table extrafield, syntax field='value' or extra.field=value
				$keyList = (empty($InfoFieldList[2]) ? 'rowid' : $InfoFieldList[2] . ' as rowid');

				if (count($InfoFieldList) > 3 && ! empty($InfoFieldList[3])) {
					list ( $parentName, $parentField ) = explode('|', $InfoFieldList[3]);
					$keyList .= ', ' . $parentField;
				}
				if (count($InfoFieldList) > 4 && ! empty($InfoFieldList[4])) {
					if (strpos($InfoFieldList[4], 'extra.') !== false) {
						$keyList = 'main.' . $InfoFieldList[2] . ' as rowid';
					} else {
						$keyList = $InfoFieldList[2] . ' as rowid';
					}
				}

				$fields_label = explode('|', $InfoFieldList[1]);
				if (is_array($fields_label)) {
					$keyList .= ', ';
					$keyList .= implode(', ', $fields_label);
				}

				$sqlwhere = '';
				$sql = 'SELECT ' . $keyList;
				$sql .= ' FROM ' . MAIN_DB_PREFIX . $InfoFieldList[0];
				if (! empty($InfoFieldList[4])) {

					// can use SELECT request
					if (strpos($InfoFieldList[4], '$SEL$')!==false) {
						$InfoFieldList[4]=str_replace('$SEL$', 'SELECT', $InfoFieldList[4]);
					}

					// current object id can be use into filter
					if (strpos($InfoFieldList[4], '$ID$')!==false && !empty($objectid)) {
						$InfoFieldList[4]=str_replace('$ID$', $objectid, $InfoFieldList[4]);
					} else {
						$InfoFieldList[4]=str_replace('$ID$', '0', $InfoFieldList[4]);
					}

					// We have to join on extrafield table
					if (strpos($InfoFieldList[4], 'extra') !== false) {
						$sql .= ' as main, ' . MAIN_DB_PREFIX . $InfoFieldList[0] . '_extrafields as extra';
						$sqlwhere .= ' WHERE extra.fk_object=main.' . $InfoFieldList[2] . ' AND ' . $InfoFieldList[4];
					} else {
						$sqlwhere .= ' WHERE ' . $InfoFieldList[4];
					}
				} else {
					

					//Modif
					if($idtiers)
						if($isExterieur)
							$sqlwhere .= 'SELECT m.rowid FROM llx_materielclient_materiel as m JOIN llx_societe as t ON m.fk_soc = t.rowid WHERE t.rowid = '.$idtiers.' AND (m.emplacement IS NULL OR m.emplacement = 0)';
						else
							$sqlwhere .= 'SELECT m.rowid FROM llx_materielclient_materiel as m JOIN llx_societe as t ON m.fk_soc = t.rowid WHERE t.rowid = '.$idtiers.' AND m.emplacement = 1';
					else
						$sqlwhere .= ' WHERE 1=1';
				}
				// Some tables may have field, some other not. For the moment we disable it.
				if (in_array($InfoFieldList[0], array ('tablewithentity')))
				{
					$sqlwhere .= ' AND entity = ' . $conf->entity;
				}
				// $sql.=preg_replace('/^ AND /','',$sqlwhere);
				

				$sql .= $sqlwhere;
				dol_syslog(get_class($this) . '::showInputField type=chkbxlst', LOG_DEBUG);

				$resql = $this->db->query($sql);
				if ($resql) {
					$num = $this->db->num_rows($resql);
					$i = 0;

					$data=array();

					while ( $i < $num ) {
						$labeltoshow = '';
						$obj = $this->db->fetch_object($resql);

						$notrans = false;
						// Several field into label (eq table:code|libelle:rowid)
						$fields_label = explode('|', $InfoFieldList[1]);
						if (is_array($fields_label)) {
							$notrans = true;
							foreach ($fields_label as $field_toshow) {
								$labeltoshow .= $obj->$field_toshow . ' ';
							}
						} else {
							$labeltoshow = $obj->{$InfoFieldList[1]};
						}
						$labeltoshow = dol_trunc($labeltoshow, 45);

						if (is_array($value_arr) && in_array($obj->rowid, $value_arr)) {
							foreach ($fields_label as $field_toshow) {
								$translabel = $langs->trans($obj->$field_toshow);
								if ($translabel != $obj->$field_toshow) {
									$labeltoshow = dol_trunc($translabel, 18) . ' ';
								} else {
									$labeltoshow = dol_trunc($obj->$field_toshow, 18) . ' ';
								}
							}

							$data[$obj->rowid]=$labeltoshow;
						} else {
							if (! $notrans) {
								$translabel = $langs->trans($obj->{$InfoFieldList[1]});
								if ($translabel != $obj->{$InfoFieldList[1]}) {
									$labeltoshow = dol_trunc($translabel, 18);
								} else {
									$labeltoshow = dol_trunc($obj->{$InfoFieldList[1]}, 18);
								}
							}
							if (empty($labeltoshow))
								$labeltoshow = '(not defined)';

								if (is_array($value_arr) && in_array($obj->rowid, $value_arr)) {
									$data[$obj->rowid]=$labeltoshow;
								}

								if (! empty($InfoFieldList[3]) && $parentField) {
									$parent = $parentName . ':' . $obj->{$parentField};
								}

								$data[$obj->rowid]=$labeltoshow;
						}

						$i ++;
					}
					$this->db->free($resql);

					$out=$form->multiselectarray($keyprefix.$key.$keysuffix, $data, $value_arr, '', 0, '', 0, '100%');
				} else {
					print 'Error in request ' . $sql . ' ' . $this->db->lasterror() . '. Check setup of extra parameters.<br>';
				}
			}
		}
		elseif ($type == 'link')
		{
			//print link;








			//require_once DOL_DOCUMENT_ROOT.'/core/class/html.form.class.php';
			require_once DOL_DOCUMENT_ROOT.'/custom/materielclient/class/formgroupemateriels.class.php';

			$form=new FormGroupeMateriel($this->db);
			
			$param_list=array_keys($param['options']);				// $param_list='ObjectName:classPath'
			$showempty=(($required && $default != '')?0:1);
			//print strval($form);

			$out=$form->selectForFormsGroupe($param_list[0], $keyprefix.$key.$keysuffix, $value, $showempty,'','','','',0,$idtiers,$isExterieur);










			if ($conf->global->MAIN_FEATURES_LEVEL >= 2)
			{
            			list($class,$classfile)=explode(':', $param_list[0]);
            			if (file_exists(dol_buildpath(dirname(dirname($classfile)).'/card.php'))) $url_path=dol_buildpath(dirname(dirname($classfile)).'/card.php', 1);
            			else $url_path=dol_buildpath(dirname(dirname($classfile)).'/'.$class.'_card.php', 1);
            			$out.='<a class="butActionNew" href="'.$url_path.'?action=create&backtopage='.$_SERVER['PHP_SELF'].'"><span class="fa fa-plus-circle valignmiddle"></span></a>';
            			// TODO Add Javascript code to add input fields contents to new elements urls
			}
		}
		elseif ($type == 'password')
		{
			// If prefix is 'search_', field is used as a filter, we use a common text field.
			$out='<input type="'.($keyprefix=='search_'?'text':'password').'" class="flat '.$morecss.'" name="'.$keyprefix.$key.$keysuffix.'" id="'.$keyprefix.$key.$keysuffix.'" value="'.$value.'" '.($moreparam?$moreparam:'').'>';
		}
		elseif ($type == 'array')
		{
			$newval = $val;
			$newval['type'] = 'varchar(256)';

			$out='';

			$inputs = array();
			if(! empty($value)) {
				foreach($value as $option) {
					$out.= '<span><a class="'.dol_escape_htmltag($keyprefix.$key.$keysuffix).'_del" href="javascript:;"><span class="fa fa-minus-circle valignmiddle"></span></a> ';
					$out.= $this->showInputField($newval, $keyprefix.$key.$keysuffix.'[]', $option, $moreparam, '', '', $morecss).'<br></span>';
				}
			}

			$out.= '<a id="'.dol_escape_htmltag($keyprefix.$key.$keysuffix).'_add" href="javascript:;"><span class="fa fa-plus-circle valignmiddle"></span></a>';

			$newInput = '<span><a class="'.dol_escape_htmltag($keyprefix.$key.$keysuffix).'_del" href="javascript:;"><span class="fa fa-minus-circle valignmiddle"></span></a> ';
			$newInput.= $this->showInputField($newval, $keyprefix.$key.$keysuffix.'[]', '', $moreparam, '', '', $morecss).'<br></span>';

			if(! empty($conf->use_javascript_ajax)) {
				$out.= '
					<script>
					$(document).ready(function() {
						$("a#'.dol_escape_js($keyprefix.$key.$keysuffix).'_add").click(function() {
							$("'.dol_escape_js($newInput).'").insertBefore(this);
						});

						$(document).on("click", "a.'.dol_escape_js($keyprefix.$key.$keysuffix).'_del", function() {
							$(this).parent().remove();
						});
					});
					</script>';
			}
		}
		if (!empty($hidden)) {
			$out='<input type="hidden" value="'.$value.'" name="'.$keyprefix.$key.$keysuffix.'" id="'.$keyprefix.$key.$keysuffix.'"/>';
		}
		/* Add comments
		 if ($type == 'date') $out.=' (YYYY-MM-DD)';
		 elseif ($type == 'datetime') $out.=' (YYYY-MM-DD HH:MM:SS)';
		 */
		
		return $out;
	}


}

/**
 * Class GroupeMaterielsLine. You can also remove this and generate a CRUD class for lines objects.
 */
class GroupeMaterielsLine
{
	// To complete with content of an object GroupeMaterielsLine
	// We should have a field rowid, fk_groupemateriels and position
}
