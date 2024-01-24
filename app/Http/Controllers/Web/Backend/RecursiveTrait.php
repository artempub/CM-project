<?php

trait RecursiveTrait {

    public function get_hierarchy_parents_ids($child_users, $role_id){
        foreach ($child_users as $key => $child_user) {
            if ($child_user->role_id == $role_id) {
                # add user ID   
                // $this->child_users_array = array_add($this->child_users_array, $key, $child_user->id); 
                array_push($this->child_users_array, $child_user->id);  
            }
            if(count($child_user->childs)){
                $this->get_hierarchy_parents_ids($child_user->childs, $role_id);
            }
        }
    }
}