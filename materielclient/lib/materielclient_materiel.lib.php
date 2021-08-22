<?php
/* Copyright (C) ---Put here your own copyright and developer email---
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * \file    lib/materielclient_materiel.lib.php
 * \ingroup materielclient
 * \brief   Library files with common functions for Materiel
 */

/**
 * Prepare array of tabs for Materiel
 *
 * @param	Materiel	$object		Materiel
 * @return 	array					Array of tabs
 */
function materielPrepareHead($object)
{
	global $db, $langs, $conf;

	$langs->load("materielclient@materielclient");

	$h = 0;
	$head = array();

	$head[$h][0] = dol_buildpath("/materielclient/materiel_card.php", 1).'?id='.$object->id;
	$head[$h][1] = $langs->trans("Card");
	$head[$h][2] = 'card';
	$h++;

	if (isset($object->fields['note_public']) || isset($object->fields['note_private']))
	{
		$nbNote = 0;
		if (!empty($object->note_private)) $nbNote++;
		if (!empty($object->note_public)) $nbNote++;
		$head[$h][0] = dol_buildpath('/materielclient/materiel_note.php', 1).'?id='.$object->id;
		$head[$h][1] = $langs->trans('Notes');
		if ($nbNote > 0) $head[$h][1].= ' <span class="badge">'.$nbNote.'</span>';
		$head[$h][2] = 'note';
		$h++;
	}

	require_once DOL_DOCUMENT_ROOT.'/core/lib/files.lib.php';
	require_once DOL_DOCUMENT_ROOT.'/core/class/link.class.php';
	$upload_dir = $conf->materielclient->dir_output . "/materiel/" . dol_sanitizeFileName($object->ref);
	$nbFiles = count(dol_dir_list($upload_dir, 'files', 0, '', '(\.meta|_preview.*\.png)$'));
	$nbLinks=Link::count($db, $object->element, $object->id);
	$head[$h][0] = dol_buildpath("/materielclient/materiel_document.php", 1).'?id='.$object->id;
	$head[$h][1] = $langs->trans('Documents');
	if (($nbFiles+$nbLinks) > 0) $head[$h][1].= ' <span class="badge">'.($nbFiles+$nbLinks).'</span>';
	$head[$h][2] = 'document';
	$h++;

	$head[$h][0] = dol_buildpath("/materielclient/materiel_agenda.php", 1).'?id='.$object->id;
	$head[$h][1] = $langs->trans("Events");
	$head[$h][2] = 'agenda';
	$h++;

	$head[$h][0] = dol_buildpath("/materielclient/intervention_list.php", 1).'?id_matos='.$object->id;
	$head[$h][1] = $langs->trans("Interventions (".getNumInterventions($object->id).')');
	$head[$h][2] = 'intervention';
	$h++;

	$head[$h][0] = dol_buildpath("/custom/materielclient/groupemateriels_list.php", 1).'?id_matos='.$object->id;
	$head[$h][1] = $langs->trans("Ensemble");
	$head[$h][2] = 'referenceonglet';
	$h++;

	// Show more tabs from modules
	// Entries must be declared in modules descriptor with line
	//$this->tabs = array(
	//	'entity:+tabname:Title:@materielclient:/materielclient/mypage.php?id=__ID__'
	//); // to add new tab
	//$this->tabs = array(
	//	'entity:-tabname:Title:@materielclient:/materielclient/mypage.php?id=__ID__'
	//); // to remove a tab
	complete_head_from_modules($conf, $langs, $object, $head, $h, 'materiel@materielclient');

	return $head;
}

function getNumInterventions($idMatos){
	//Retourne vrai si le materiel d'id $idParam est exterieur faux sinon

	
	global $db;

	$requete = 'SELECT COUNT(*) as c FROM llx_materielclient_intervention as t WHERE t.fk_materiel = '.$idMatos;
	
	$resqlf=$db->query($requete);
	
	if ($resqlf)
	{
		
		$numf = $db->num_rows($resqlf);
		$i = 0;
		if ($numf)
		{
			
			while ($i < $numf)
			{
				
				$objf = $db->fetch_object($resqlf);
				if ($objf)
				{
					
					// You can use here results
					return $objf->c;
				}
				$i++;
			}
		}
	}
	return false;

}