<?php
/**
 * The Class is used to allow developers to create
 * custom alerts
 */
class WSAL_Sensors_UserMetaHooks extends WSAL_AbstractSensor
{
    public function HookEvents()
    {
        add_action('added_user_meta', array($this,   'EventUserMetaCreated'), 10, 3);
        add_action('update_user_meta', array($this,  'EventUserMetaUpdating'), 10, 4);
        add_action('updated_user_meta', array($this, 'EventUserMetaUpdated'), 10, 4);
        add_action('deleted_user_meta', array($this, 'EventUserMetaDeleted'), 10, 4);
    }
    protected $old_meta = array();

    protected function CanLogUserMeta($object_id, $meta_key)
    {
        //check if excluded meta key or starts with _
        if (substr($meta_key, 0, 1) == '_') {
            return false;
        } else if ($this->IsExcludedCustomFields($meta_key)) {
            return false;
        } else {
            return true;
        }
    }

    public function IsExcludedCustomFields($custom)
    {
        $customFields = $this->plugin->settings->GetExcludedMonitoringCustom();

        if (in_array($custom, $customFields)) {
            return true;
        }
        foreach ($customFields as $field) {
            if (strpos($field, "*") !== false) {
                // wildcard str[any_character] when you enter (str*)
                if (substr($field, -1) == '*') {
                    $field = rtrim($field, '*');
                    if (preg_match("/^$field/", $custom)) {
                        return true;
                    }
                }
                // wildcard [any_character]str when you enter (*str)
                if (substr($field, 0, 1) == '*') {
                    $field = ltrim($field, '*');
                    if (preg_match("/$field$/", $custom)) {
                        return true;
                    }
                }
            }
        }
        return false;
    }

    public function EventUserMetaCreated($meta_id, $object_id, $meta_key, $meta_value = null)
    {
        $user = get_user_by('id',$object_id);
        $this->plugin->alerts->Trigger(8501, array(
            'UserID' => $object_id,
            'DisplayName' => $user->display_name,
            'MetaKey' => $meta_key,
            'MetaValue' => $meta_value,
        ));

    }

    public function EventUserMetaUpdating($meta_id, $object_id, $meta_key, $meta_value = null)
    {
        static $meta_type = 'user';
        $this->old_meta[$meta_id] = (object)array(
            'key' => ($meta = get_metadata_by_mid($meta_type, $meta_id)) ? $meta->meta_key : $meta_key,
            'val' => get_metadata($meta_type, $object_id, $meta_key, true),
        );
    }

    public function EventUserMetaUpdated($meta_id, $object_id, $meta_key, $meta_value = null)
    {
        $user = get_user_by('id',$object_id);

          if (!$this->CanLogUserMeta($object_id, $meta_key)) return;

            if (isset($this->old_meta[$meta_id])) {
                // check change in meta key
                if ($this->old_meta[$meta_id]->key != $meta_key) {
                            $this->plugin->alerts->Trigger(8502, array(
                                'UserID' => $object_id,
                                'DisplayName' => $user->display_name,
                                'MetaID' => $meta_id,
                                'MetaKeyNew' => $meta_key,
                                'MetaKeyOld' => $this->old_meta[$meta_id]->key,
                                'MetaValue' => $meta_value,
                            ));
                } else if ($this->old_meta[$meta_id]->val != $meta_value) { // check change in meta value
                            $this->plugin->alerts->Trigger(8503, array(
                                'UserID' => $object_id,
                                'DisplayName' => $user->display_name,
                                'MetaID' => $meta_id,
                                'MetaKey' => $meta_key,
                                'MetaValueNew' => $meta_value,
                                'MetaValueOld' => $this->old_meta[$meta_id]->val,
                            ));
                }
                // remove old meta update data
                unset($this->old_meta[$meta_id]);
            }

    }

    public function EventUserMetaDeleted($meta_ids, $object_id, $meta_key, $meta_value = null)
    {
        $user = get_user_by('id',$object_id);

             foreach ($meta_ids as $meta_id) {
             //   if (!$this->CanLogPostMeta($object_id, $meta_key)) continue;

                   $this->plugin->alerts->Trigger(8504, array(
                            'UserID' => $object_id,
                       'DisplayName' => $user->display_name,
                            'MetaID' => $meta_id,
                            'MetaKey' => $meta_key,
                            'MetaValue' => $meta_value,
                        ));
            }

    }

}
