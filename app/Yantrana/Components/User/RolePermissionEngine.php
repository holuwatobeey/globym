<?php
/*
* RolePermissionEngine.php - Main component file
*
* This file is part of the User component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\User;

use App\Yantrana\Core\BaseEngine;
 
use App\Yantrana\Components\User\Repositories\RolePermissionRepository;

use YesAuthority;

class RolePermissionEngine extends BaseEngine
{
     
    /**
     * @var  RolePermissionRepository $rolePermissionRepository - RolePermission Repository
     */
    protected $rolePermissionRepository;
    

    /**
      * Constructor
      *
      * @param  RolePermissionRepository $rolePermissionRepository - RolePermission Repository
      *
      * @return  void
      *-----------------------------------------------------------------------*/

    public function __construct(RolePermissionRepository $rolePermissionRepository)
    {
        $this->rolePermissionRepository = $rolePermissionRepository;
    }
    
    /**
    * Prepare Role Add Support Data
    *
    * @return  array
    *---------------------------------------------------------------- */
    public function prepareAddSupportData()
    {
        $roleCollection = $this->rolePermissionRepository->fetchAll();
        $roleData = [];

        // check if role collection exist
        if (!__isEmpty($roleCollection)) {
            foreach ($roleCollection as $key => $role) {
                // check if role admin not exist
                if ($role->_id !== 1) { // admin
                    $roleData[] = [
                        'id'    => $role->_id,
                        'name'  => $role->title
                    ];
                }
            }
        }

        $permissionCollection = YesAuthority::withDetails()
                                            ->getZones();
                                             
        $permissionData = [];
        $setParentName = '';
        
        if (!__isEmpty($permissionCollection)) {
            foreach ($permissionCollection as $key => $permission) {
            	$result = 1; // Inherited

	            if ($permission->resultBy() === 'CONFIG_ROLE') {
	                $result = 1; // Inherited
	            }

	            if ($permission->resultBy() === 'DB_ROLE') {
	                // Check if allowed permission
	                if ($permission->isAccess() === true) {
	                    $result = 2; // Allow
	                }

	                // Check if denied permission
	                if ($permission->isAccess() !== true) {
	                    $result = 3; // Deny
	                }
	            }

	            $currentInheritStatus   = false; // not available

	            // Check if level check array has 'CONFIG_ROLE' key and it is true
	            if ((array_has($permission->levelsChecked(), 'CONFIG_ROLE'))
	                and ($permission->levelsChecked()['CONFIG_ROLE'] === true)) {
	                $currentInheritStatus = true; // available
	            }

                $dependencies           = $permission->dependencies();
                $accessIdKey            = $permission->access_id_key();

                // if (__isEmpty($dependencies)) {
                //     $setParentName = $accessIdKey;
                // }

                // $group = '';
                // $dependent = array_get($dependencies, '0');

                // if (is_null($dependent)) {
                //     $dependent = $setParentName;
                // }

                $permissionData[] = [
                	"id"            => $permission->accessIdKey(),
                	"key"            => $permission->accessIdKey(),
                	'name'          => $accessIdKey,
                    'title'         => $permission->title(),
                    "parent"        => $permission->parent(),
                    'disabled'      => false,
                    "result"        => $result,
                    'dependencies'  => $dependencies,
                    'access_id_key' => $accessIdKey,
                    // 'dependent'     => $dependent,
                    'inheritStatus' => $currentInheritStatus
                    //'is_access'   => $permission->is_access(),
                ];
            }
        }
        
        $allPermissions = $this->buildTree($permissionData);
 		$allowPermissions = [];
 		$denyPermissions = [];

 		if (!__isEmpty($allPermissions)) {
 			foreach ($allPermissions as $permission) {
 				if (!__isEmpty($permission['children'])) {
 					foreach ($permission['children'] as $child) {
						if ($child['result'] == 2) {
                            $allowPermissions[] = $child['id'];
                        }
                        if ($child['result'] == 3) {
                            $denyPermissions[] = $child['id'];
                        }
                        if ($child['result'] == 1) {
                            $inheritPermissions[] = $child['id'];
                        }
					}
 				}
 				if (isset($permission['children_permission_group'])) {
 					 
 					foreach ($permission['children_permission_group'] as $groupchild) {

 						foreach ($groupchild['children'] as $subchild) {
 						
							if ($subchild['result'] == 2) {
								$allowPermissions[] = $subchild['id'];
							}
							if ($subchild['result'] == 3) {
								$denyPermissions[] = $subchild['id'];
							}
						}
					}
 				}
 			}
 		}

 		// __dd([
   //          'permissions' => $allPermissions,
   //          'allow_permissions' => $allowPermissions,
   //          'deny_permissions' => $denyPermissions,
   //          'inherit_permissions' => $inheritPermissions
   //      ]);
        return $this->engineReaction(1, [
            'permissions' => $allPermissions,
            'allow_permissions' => $allowPermissions,
            'deny_permissions' => $denyPermissions,
            'userRoles'         => $roleData,
        ]);

        // return $this->engineReaction(1, [
        //     'userRoles'         => $roleData,
        //     'permissionData'    => $permissionData,
        //     'permissionList'    => configItem('user.permission_status', [2, 3])
        // ]);
    }

    /*
     * Prepare Nested key value array.
     *
     * @param array $elements
     * @param int $parentId
     *
     * @return array
     *---------------------------------------------------------------- */

    protected function buildTree($elements = [], $parentId = '')
    {
        $branch = [];
        $permissionStatuses = configItem('user.permission_status');
        unset($permissionStatuses[1]);
        foreach ($elements as $element) {
            if ($element['parent'] == $parentId) {
                $children = $this->buildTree($elements, $element['id']);
  
                if ($children) {
                	foreach ($children as $key => $subparent) {
 						if (__isEmpty($this->buildTree($elements, $subparent['id']))) {

 							$element['children'][] = $subparent;
 							foreach ($permissionStatuses as $statusKey => $status) {

                                $inheritStatus = '';

                                if ($statusKey == 1) {
                                    $inheritStatus = ($subparent['inheritStatus'] == true)
                                                    ? __tr(' (Allow)') : __tr(' (Deny)');
                                }

                                $element['children'][$key]['options'][] = [
                                    'title'     => $status.$inheritStatus,
                                    'status'	=> $statusKey,
                                    'key'       => $subparent['id'].'_'.$statusKey,
                                    'id'        => $subparent['id'].'_'.$statusKey,
                                ];
                            }
 						} else {
 							$element['children_permission_group'][] = $subparent;
 						}

                        $subparent['result'] = $element['result'];
                	}
             	}
                $branch[] = $element;
            }
        }

        return $branch;
    }

    /**
    * Prepare Permission by role id
    *
    * @param number $roleId
    *
    * @return  array
    *---------------------------------------------------------------- */
    public function preparePermissionByRoleId($roleId)
    {
        $permissionCollection = YesAuthority::checkOnly(['CONFIG_ROLE', 'DB_ROLE'])
                                            ->viaRole()
                                            ->withDetails()
                                            ->getZones($roleId);

        $permissionData = $allowedData = [];

        if (!__isEmpty($permissionCollection)) {
            foreach ($permissionCollection as $key => $permission) {

                $result = 1; // Inherited

                if ($permission->resultBy() === 'CONFIG_ROLE') {
                    //$result = 1; // Inherited
                    if ($permission->isAccess() === true) {
                        $result = 2; // Allow
                    }

                    // Check if denied permission
                    if ($permission->isAccess() !== true) {
                        $result = 3; // Deny
                    }
                }

                if ($permission->resultBy() === 'DB_ROLE') {
                    // Check if allowed permission
                    if ($permission->isAccess() === true) {
                        $result = 2; // Allow
                    }

                    // Check if denied permission
                    if ($permission->isAccess() !== true) {
                        $result = 3; // Deny
                    }
                }

                $currentInheritStatus   = false; // not available

                // Check if level check array has 'CONFIG_ROLE' key and it is true
                if ((array_has($permission->levelsChecked(), 'CONFIG_ROLE'))
                    and ($permission->levelsChecked()['CONFIG_ROLE'] === true)) {
                    $currentInheritStatus = true; // available
                }

                $permissionData[] = [
                    "id"            => $permission->access_id_key(),
                    "title"         => str_replace('Read ', '', $permission->title()),
                    "parent"        => $permission->parent(),
                    "folder"        => false,
                    "key"           => $permission->access_id_key(),
                    "result"        => $result,
                    "expanded"      => true,
                    "dependencies"  => $permission->dependencies(),
                    "checkbox"      => false,
                    "inheritStatus" => $currentInheritStatus
                ];

                //if ($permission->is_access()) {
                    $allowedData[] = $permission->access_id_key().'_'.$result;
                //}
            }
        }
         
     	$allPermissions = $this->buildTree($permissionData);
 		$allowPermissions = [];
 		$denyPermissions = [];
 
 		if (!__isEmpty($allPermissions)) {
 			foreach ($allPermissions as $permission) {
 				if (!__isEmpty($permission['children'])) {
 					foreach ($permission['children'] as $child) {
						if ($child['result'] == 2) {
							$allowPermissions[] = $child['id'];
						}
						if ($child['result'] == 3) {
							$denyPermissions[] = $child['id'];
						}
					}
 				}
 				if (isset($permission['children_permission_group'])) {
 					 
 					foreach ($permission['children_permission_group'] as $groupchild) {

 						foreach ($groupchild['children'] as $subchild) {
 						
							if ($subchild['result'] == 2) {
								$allowPermissions[] = $subchild['id'];
							}
							if ($subchild['result'] == 3) {
								$denyPermissions[] = $subchild['id'];
							}
						}
					}
 				}
 			}
 		}
 
        return $this->engineReaction(1, [
            'permissionData' => $allPermissions,
            'allow_permissions' => $allowPermissions,
            'deny_permissions' => $denyPermissions,
          	'allowedData' => $allowedData
        ]);
    }

    /**
    * Process Add New Role
    *
    * @param array $inputData
    *
    * @return  array
    *---------------------------------------------------------------- */
    public function processAddNewRole($inputData)
    {
        $storeData = [
            'title' => $inputData['title'],
            '__permissions' => [
                'allow' => $inputData['allow_permissions'],
                'deny'  => $inputData['deny_permissions']
            ]
        ];

        if ($this->rolePermissionRepository->store($storeData)) {
            return $this->engineReaction(1, null, __tr('Role Permission Added Successfully.'));
        }

        return $this->engineReaction(2, null, __tr('Role Permission Not Added.'));
    }
    
    /**
      * RolePermission datatable source
      *
      * @return  array
      *---------------------------------------------------------------- */
 
    public function prepareRolePermissionDataTableSource()
    {
        $rolePermissionCollection = $this->rolePermissionRepository
                            ->fetchRolePermissionDataTableSource();
        $requireColumns = [
            '_id',
            'title',
            'can_manage_permission' => function() {
                return canAccess('manage.user.role_permission.write.create');
            },
            'can_delete' => function() {
                return canAccess('manage.user.role_permission.write.delete');
            }
        ];

        return $this->dataTableResponse($rolePermissionCollection, $requireColumns);
    }
 

    /**
      * Role Permission delete process
      *
      * @param  mix $rolePermissionIdOrUid
      *
      * @return  array
      *---------------------------------------------------------------- */

    public function processRolePermissionDelete($rolePermissionIdOrUid)
    {
        $rolePermission = $this->rolePermissionRepository
                                ->fetch($rolePermissionIdOrUid);
      
        // Check if role permission exist
        if (__isEmpty($rolePermission)) {
            return $this->engineReaction(18, null, __tr('Role Permission Not Found.'));
        }

        // Fetch user data with count
        $userData = $this->rolePermissionRepository->fetchUserCount($rolePermission->_id);
     
        $userCount = $userData->users_count;
        
        // Check if current role contain any user
        if ($userCount !== 0) {
            return $this->engineReaction(2, null, __tr('This Role contain __userCount__ __user__, you need to delete related __user__ first.', [
                    '__userCount__' => $userCount,
                    '__user__'      => ($userCount == 1) ? 'user' : 'users'
                ]));
        }

        // Check if Role Permission deleted
        if ($this->rolePermissionRepository->deleteRolePermission($rolePermission)) {
            return $this->engineReaction(1, null, __tr('Role Permission deleted Successfully.'));
        }

        return $this->engineReaction(2, null, __tr('Role Permission not deleted.'));
    }

    /**
      * Prepare Role Permissions
      *
      * @param number $roleId
      *
      * @return  array
      *---------------------------------------------------------------- */
    public function preparePermissions($roleId)
    {
        // Check if role is admin
        if ($roleId == 1) { // Admin
            return $this->engineReaction(2, null, __tr('You Dont Have Permission To Change Admin Permissions.'));
        }

        $permissions = [];

        // Get all available permissions
        $availablePermissions = YesAuthority::checkOnly(['CONFIG_ROLE', 'DB_ROLE'])
                                            ->viaRole()
                                            ->withDetails()
                                            ->getZones($roleId);
                                               
        foreach ($availablePermissions as $key => $permission) {

            $result = 1; // Inherited

            if ($permission->resultBy() === 'CONFIG_ROLE') {
                $result = 1; // Inherited
            }

            if ($permission->resultBy() === 'DB_ROLE') {
                // Check if allowed permission
                if ($permission->isAccess() === true) {
                    $result = 2; // Allow
                }

                // Check if denied permission
                if ($permission->isAccess() !== true) {
                    $result = 3; // Deny
                }
            }

            $currentInheritStatus   = false; // not available

            // Check if level check array has 'CONFIG_ROLE' key and it is true
            if ((array_has($permission->levelsChecked(), 'CONFIG_ROLE'))
                and ($permission->levelsChecked()['CONFIG_ROLE'] === true)) {
                $currentInheritStatus = true; // available
            }

            $permissions[] = [
                "id"            => $permission->accessIdKey(),
                "title"         => str_replace('Read ', '', $permission->title()),
                //"parent_id"     => $permission->parent(),
                "parent"        => $permission->parent(),
                // "folder"        => false,//true,
                "key"           => $permission->accessIdKey(),
                //"selected"    => $permission->is_access(),
                "result"        => $result,
                // "expanded"      => true,
                "dependencies"  => $permission->dependencies(),
                // "checkbox"      => false,
                'inheritStatus' => $currentInheritStatus
            ];  
        }
 		
 		$allPermissions = $this->buildTree($permissions);
 		$allowPermissions = [];
 		$denyPermissions = [];
 		$inheritPermissions = [];

 		if (!__isEmpty($allPermissions)) {
 			foreach ($allPermissions as $permission) {
 				if (!__isEmpty($permission['children'])) {
 					foreach ($permission['children'] as $child) {
						if ($child['result'] == 2) {
							$allowPermissions[] = $child['id'];
						}
						if ($child['result'] == 3) {
							$denyPermissions[] = $child['id'];
						}
					}
 				}
 				if (isset($permission['children_permission_group'])) {
 					 
 					foreach ($permission['children_permission_group'] as $groupchild) {

 						foreach ($groupchild['children'] as $subchild) {
 						
							if ($subchild['result'] == 2) {
								$allowPermissions[] = $subchild['id'];
							}
							if ($subchild['result'] == 3) {
								$denyPermissions[] = $subchild['id'];
							}
						}
					}
 				}
 			}
 		}
 
        return $this->engineReaction(1, [
            'permissions' => $allPermissions,
            'allow_permissions' => $allowPermissions,
            'deny_permissions' => $denyPermissions,
         ]);
    }

    /**
      * Process Create Role Permission
      *
      * @param number $roleId
      * @param array $inputData
      *
      * @return  array
      *---------------------------------------------------------------- */
    public function processCreateRolePermission($roleId, $inputData)
    {
        $userRoleData = $this->rolePermissionRepository->fetch($roleId);

        // Check if user role data exist
        if (__isEmpty($userRoleData)) {
            return $this->engineReaction(18, null, __tr('User Role Not Exist'));
        }

        // role permission update data
        $updateData = [
            '__permissions' => [
                'allow' => $inputData['allow_permissions'],
                'deny'  => $inputData['deny_permissions']
            ]
        ];

        // Check if role permission updated
        if ($this->rolePermissionRepository->updateUserRole($userRoleData, $updateData)) {
            return $this->engineReaction(1, null, __tr('User Role Permission Updated Successfully.'));
        }

        return $this->engineReaction(14, null, __tr('Nothing Updated.'));
    }
}
