<?php

class PushON_ProductCreator_Helper_Permutations extends Mage_Core_Helper_Abstract {

    function getPermutations($data) {
        $perms = array();
        foreach ($data as $key => $val) {
            $perms = $this->getPermutation($val, $perms);
        }
        return $perms;
    }

    function getPermutation($data, $perms) {
        if (count($perms) <= 0)
            return $data;

        $ret = array();

        foreach ($perms as $aK => $aV) {
            foreach ($data as $bK => $bV) {
                $ret[] = $aV . ',' . $bV;
            }
        }

        return $ret;
    }

}
