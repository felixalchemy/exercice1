<?php

/**
 * Output a string describing a file tree filtered by the '.jpg' extension.
 *
 * @usage php listJPG.php <path> <hideEmptyDirectory>
 *
 * @example "php listJPG.php /home"
 * @example "php listJPG.php /home hide"
 */
echo listRecurseJPG($argv[1] ?? '.', $argv[2] ?? false);


/**
 * Returns a string describing a file tree filtered by the '.jpg' extension.
 *
 * @param $pPath Entry path
 * @param bool $pHideEmptyDirectories (optionnal) Hide empty directory flag if set on true
 * @param int $pDepth internal usage, leave non-assigned
 *
 * @return string Return file tree
 */
function listRecurseJPG($pPath, $pHideEmptyDirectories = false, $pDepth = 0)
{
	$output = '';

	if (!@is_dir($pPath))
	{
		die('Error. \'' . $pPath . '\' is not a directory.');
	}

	if (!$dir = @opendir($pPath))
	{
		die('Error. \'' . $pPath . '\' access error.');
	}

	/**
	 * Loop on each directory entry.
	 */
	while ($entryDirectory = readdir($dir))
	{
		/**
		 * Bypass specials entries.
		 */
		if (($entryDirectory != '.') && ($entryDirectory != '..'))
		{
			/**
			 * Temporary storage (and format) current entry.
			 */
			$bufferEntryDirectory = str_repeat(str_repeat(' ', 7), $pDepth) . $entryDirectory . "\n";

			$currentEntryPath = $pPath . '/' . $entryDirectory;

			if (is_dir($currentEntryPath))
			{
				/**
				 * Recurse call for entry typed as directory
				 */
				$bufferChilds = listRecurseJPG($currentEntryPath, $pHideEmptyDirectories, $pDepth + 1);

				/**
				 * Output directory entry and child(s).
				 * If $pHideEmptyDirectories flag on, bypass output if no child found.
				 */
				if (!($pHideEmptyDirectories && $bufferChilds == ''))
				{
					$output .= $bufferEntryDirectory . $bufferChilds;
				}
			}
			else
			{
				/**
				 * Output entry directory for JPG extension file
				 */
				if (strtolower(pathinfo($currentEntryPath, PATHINFO_EXTENSION)) == 'jpg')
				{
					$output .= $bufferEntryDirectory;
				}
			}
		}
	}

	return ($output);
}