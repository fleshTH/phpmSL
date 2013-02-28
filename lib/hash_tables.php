
	function mirc_hmake($args) { 
		if ($args[0][0] == "-") { 
			array_shift($args);
		}
		$table = strtolower($args[0]);
		if (!isset(mSL::$hash_tables[$table])) { 
			mSL::$hash_tables[$table] = Array();
		}
	}
	function mirc_hadd($args) { 
		if ($args[0][0] == "-") { 
			$switch = array_shift($args);
		}
		if (count($args) <= 1) return;
		$table = strtolower(array_shift($args));
		$item = strtolower(array_shift($args));
		$data = implode(" ",$args);
		if (stripos($switch,"m") !== false) { 
			$this->mirc_hmake(Array($table));
		}
		if (isset(mSL::$hash_tables[$table])) { 
			mSL::$hash_tables[$table][$item] = $data;
		}
	}
	function mirc_hdel($args) { 
		if ($args[0][0] == "-") { 
			$switch = array_shift($args);
		}
		if (count($args) <= 1) return;
		$table = array_shift($args);
		$item = array_shift($args);
		if (stripos($switch,"w") !== false) { 
			$ht = &mSL::$hash_tables[$table];
			if (is_array($ht)) { 
				foreach($ht as $k => $v) { 
					if (mSL::isWildCardMatch($k,$item)) { 
						unset($ht[$k]);
					}
				}
			}
		}
		else { 
			unset(mSL::$hash_tables[$table][$item]);
		}
	}
	function mirc_hinc($args) { 
		if ($args[0][0] == "-") { 
			$switch = array_shift($args);
		}
		if (count($args) <= 1) return;
		$table = strtolower(array_shift($args));
		$item = strtolower(array_shift($args));
		if (mSL::$hash_tables[$table]) { 
			mSL::$hash_tables[$table][$item]++;
		}
	}
	function hget($table,$item = null) { 
		$table = strtolower($table);
		if (is_numeric($table)) { 
			if ($table == 0) { 
				return count(mSL::$hash_tables);
			}
			$i = 1;
			$ht = &mSL::$hash_tables;
			foreach($ht as $k => $d) { 
				if ($i == $table) { 
					$t = $k;
					break;
				}
				$i++;
			}
		}
		else { 
			if (isset(mSL::$hash_tables[$table])) { 
				$t = $table;
			}
			else { 
				return;
			}
		}
		if ($item == null) return $t;
		$item = strtolower($item);
		if (isset(mSL::$hash_tables[$t][$item])) { 
			return mSL::$hash_tables[$t][$item];
		}
		if (is_numeric($item)) { 
			$localParams = $this->parent->getLocalParams();
			$p = $localParams['prop'];
			if ($item == 0) { 
				if ($p == "item" || $p == "data") { 
					return count(mSL::$hash_tables[$t]);
				}
				else {
					return;
				}
			}
			$i = 1;
			foreach(mSL::$hash_tables[$t] as $k => $v) { 
				if ($i == $item) { 
					if ($p == "item") { 
						return $k;
					}
					if ($p == "data") { 
						return $v;
					}
					return;
				}
				$i++;
			}
		}
	}
