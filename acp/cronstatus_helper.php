<?php
/**
 *
 * @package       Cron Status
 * @copyright (c) 2014 - 2018 Igor Lavrov and John Peskens
 * @license       http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

namespace boardtools\cronstatus\acp;

class cronstatus_helper
{
	/**
	 * Recursive array sorting based on the second level key
	 *
	 * @param array  $array Array to be sorted
	 * @param string $on    Second level key for sorting
	 * @param int    $order Sorting direction (SORT_ASC, SORT_DESC)
	 * @return array
	 */
	public function array_sort($array, $on, $order = SORT_ASC)
	{
		$new_array = array();
		$sortable_array = array();

		if (sizeof($array) > 0)
		{
			foreach ($array as $k => $v)
			{
				if (is_array($v))
				{
					foreach ($v as $k2 => $v2)
					{
						if ($k2 == $on)
						{
							$sortable_array[$k] = $v2;
						}
					}
				}
				else
				{
					$sortable_array[$k] = $v;
				}
			}

			switch ($order)
			{
				case SORT_ASC:
					asort($sortable_array);
				break;
				case SORT_DESC:
					arsort($sortable_array);
				break;
			}

			foreach ($sortable_array as $k => $v)
			{
				$new_array[$k] = $array[$k];
			}
		}
		return $new_array;
	}

	/**
	 * Performs the search for a specific config_name and
	 * returns the corresponding config_value or false if nothing was found
	 * Works like array_search with partial matches
	 *
	 * @param string $needle   The config_name to search for
	 * @param array  $haystack The array to search in
	 * @return mixed
	 */
	public function array_find($needle, $haystack)
	{
		if (!is_array($haystack))
		{
			return false;
		}
		foreach ($haystack as $key => $item)
		{
			if (strpos($item['config_name'], $needle) !== false)
			{
				return $haystack[$key]['config_value'];
			}
		}
		return false;
	}

	/**
	 * Outputs extension metadata into the template
	 *
	 * @param array                    $metadata Array with all metadata for the extension
	 * @param \phpbb\template\template $template phpBB template object
	 */
	public function output_metadata_to_template($metadata, $template)
	{
		$template->assign_vars(array(
			'META_NAME'        => $metadata['name'],
			'META_TYPE'        => $metadata['type'],
			'META_DESCRIPTION' => (isset($metadata['description'])) ? $metadata['description'] : '',
			'META_HOMEPAGE'    => (isset($metadata['homepage'])) ? $metadata['homepage'] : '',
			'META_VERSION'     => $metadata['version'],
			'META_TIME'        => (isset($metadata['time'])) ? $metadata['time'] : '',
			'META_LICENSE'     => $metadata['license'],

			'META_REQUIRE_PHP'      => (isset($metadata['require']['php'])) ? $metadata['require']['php'] : '',
			'META_REQUIRE_PHP_FAIL' => (isset($metadata['require']['php'])) ? false : true,

			'META_REQUIRE_PHPBB'      => (isset($metadata['extra']['soft-require']['phpbb/phpbb'])) ? $metadata['extra']['soft-require']['phpbb/phpbb'] : '',
			'META_REQUIRE_PHPBB_FAIL' => (isset($metadata['extra']['soft-require']['phpbb/phpbb'])) ? false : true,

			'META_DISPLAY_NAME' => (isset($metadata['extra']['display-name'])) ? $metadata['extra']['display-name'] : '',
		));

		foreach ($metadata['authors'] as $author)
		{
			$template->assign_block_vars('meta_authors', array(
				'AUTHOR_NAME'     => $author['name'],
				'AUTHOR_EMAIL'    => (isset($author['email'])) ? $author['email'] : '',
				'AUTHOR_HOMEPAGE' => (isset($author['homepage'])) ? $author['homepage'] : '',
				'AUTHOR_ROLE'     => (isset($author['role'])) ? $author['role'] : '',
			));
		}
	}
}
