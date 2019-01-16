<template>
    <!--<b-animated fade-in>  main container -->
    <div>
        <notifications group="notification" />
        <div class="animated fadeIn">
            <b-row>
                <b-col sm="12">
                    <b-card >
                        <h5 slot="header">
                            <span class="text-primary">
                                <i class="fa fa-bars"></i> 
                                User Group List
                                <small class="font-italic">List of all registered usergroup groups.</small></span>
                        </h5>
                        <b-row class="mb-2">
                            <b-col sm="4">
                                    <b-button variant="primary" @click="showModalEntry = true, entryMode='Add', clearFields('usergroup')">
                                            <i class="fa fa-plus-circle"></i> Create New User Group
                                    </b-button>
                            </b-col>

                            <b-col  sm="4">
                                <span></span>
                            </b-col>

                            <b-col  sm="4">
                                <b-form-input 
                                            v-model="filters.usergroups.criteria"
                                            type="text" 
                                            placeholder="Search">
                                </b-form-input>
                            </b-col>
                        </b-row>

                        <b-row>
                            <b-col sm="12">
                                <b-table 
                                    :filter="filters.usergroups.criteria"
                                    :fields="tables.usergroups.fields"
                                    :items.sync="tables.usergroups.items"
                                    :current-page="paginations.usergroups.currentPage"
                                    :per-page="paginations.usergroups.perPage"
                                    striped small bordered show-empty
                                >
                                    <template slot="row_data" slot-scope="row">
                                        <b-btn :size="'sm'" variant="success" @click.stop="row.toggleDetails">
                                            <i :class="row.detailsShowing ? 'fa fa-minus-circle' : 'fa fa-plus-circle'"></i>
                                        </b-btn>
                                    </template>
                                    <template slot="action" slot-scope="data">
                                        <b-btn :size="'sm'" variant="primary" @click="setUpdate(data)">
                                            <i class="fa fa-edit"></i>
                                        </b-btn>

                                        <b-btn :size="'sm'" variant="danger" @click="setDelete(data)">
                                            <i class="fa fa-trash"></i>
                                        </b-btn>
                                    </template>
                                    <template slot="row-details" slot-scope="row">
                                        <b-list-group>
                                            <b-list-group-item v-for="module_group in modules['module_groups']" :key="module_group.module_group">
                                                {{module_group.module_group}}
                                                <b-list-group class="mt-2">
                                                    <b-list-group-item v-for="module in filterModules(module_group.module_group)" :key="module.module_id" v-b-toggle="module.module_id + '-' + row.item.user_group_id">
                                                        {{module.module_name}}
                                                        <b-collapse :id="module.module_id + '-' + row.item.user_group_id">
                                                            <b-list-group class="mt-2">
                                                                <b-list-group-item v-for="right in filterModuleRights(module.module_id)" :key="right.module_right_id">
                                                                    {{right.right_desc}}
                                                                    <c-switch type="text" variant="primary" on="1" off="Off" :pill="true" :checked="true"/>
                                                                </b-list-group-item>
                                                            </b-list-group>
                                                        </b-collapse>
                                                    </b-list-group-item>
                                                </b-list-group>
                                            </b-list-group-item>
                                        </b-list-group>
                                    </template>
                                    
                                </b-table>

                                <b-pagination
                                            :align="'right'"
                                            :total-rows="paginations.usergroups.totalRows"
                                            :per-page="paginations.usergroups.perPage"
                                            v-model="paginations.usergroups.currentPage" />
                            </b-col>
                        </b-row>

                    </b-card>
                </b-col>
            </b-row>
        </div>
        <b-modal 
            v-model="showModalEntry"
            :noCloseOnEsc="true"
            :noCloseOnBackdrop="true"
        >
            <div slot="modal-title">
                User Group Entry - {{entryMode}}
            </div>
            <b-col lg=12>
                <b-form @keydown="resetFieldStates('usergroup')" autocomplete="off">
                    <b-form-group>
                        <label for="user_group">* User Group</label>
                        <b-form-input
                            ref="user_group"
                            id="user_group"
                            v-model="forms.usergroup.fields.user_group"
                            type="text"
                            placeholder="User Group"
                            error="asdf"
                            required>
                        </b-form-input>
                    </b-form-group>
                    <b-form-group>
                        <label>Description</label>
                        <b-form-textarea
                            ref="user_group_desc"
                            id="user_group_desc"
                            v-model="forms.usergroup.fields.user_group_desc"
                            type="text"
                            placeholder="Description">
                        </b-form-textarea>
                    </b-form-group>
                </b-form>
            </b-col>
            <div slot="modal-footer">
                <b-button :disabled="forms.usergroup.isSaving" variant="primary" @click="onUserGroupEntry">
                    <icon v-if="forms.usergroup.isSaving" name="sync" spin></icon>
                    <i class="fa fa-check"></i>
                    Save
                </b-button>
                <b-button variant="secondary" @click="showModalEntry=false">Close</b-button>            
            </div>
        </b-modal>
        <b-modal 
            v-model="showModalDelete"
            :noCloseOnEsc="true"
            :noCloseOnBackdrop="true"
        >
            <div slot="modal-title">
                Delete User Group
            </div>
            <b-col lg=12>
                Are you sure you want to delete this user group?
            </b-col>
            <div slot="modal-footer">
                <b-button :disabled="forms.usergroup.isSaving" variant="primary" @click="onUserGroupDelete">
                    <icon v-if="forms.usergroup.isSaving" name="sync" spin></icon>
                    <i class="fa fa-check"></i>
                    OK
                </b-button>
                <b-button variant="secondary" @click="showModalDelete=false">Close</b-button>            
            </div>
        </b-modal>
    </div>
</template>

<script>
export default {
    name: 'usergroups',
    data () {
        return {
            entryMode: 'Add',
            showModalEntry: false, //if true show modal
            showModalDelete: false,
            forms: {
                usergroup : {
                    isSaving: false,
                    fields: {
                        user_group_id: null,
                        user_group: null,
                        user_group_desc: null,
                    },
                    states: {
                        user_group_id: null,
                        user_group: null,
                        user_group_desc: null,
                    },
                    errors: {
                        user_group_id: null,
                        user_group: null,
                        user_group_desc: null,
                    }
                }
            },
            tables: {
                usergroups: {
                    fields:[
                        {
                            key:'row_data',
                            label: '',
                            tdClass: '',
                            thStyle: {width: '40px'}
                        },
                        {
                            key:'user_group',
                            label: 'User Group',
                            tdClass: 'align-middle',
                        },
                        {
                            key:'user_group_desc',
                            label: 'Desc',
                            tdClass: 'align-middle',
                        },
                        {
                            key:'action',
                            label:'',
                            thStyle: {width: '75px'}
                        }
                    ],
                    items: []
                }
            },
            filters: {
                usergroups: {
                    criteria: null
                }
            },
            paginations: {
                usergroups: {
                    totalRows: 0,
                    currentPage: 1,
                    perPage: 10
                }
            },
            user_group_id: null,
            modules: [],
            row: []
        }
    },
    methods:{
        onUserGroupEntry () {
            if(this.entryMode == 'Add'){
                this.createEntity('usergroup', true, 'usergroups')
            }
            else{
                this.updateEntity('usergroup', 'user_group_id', true, this.row)
            }
        },
        onUserGroupDelete(){
            this.deleteEntity('usergroup', this.user_group_id, true, 'usergroups', 'user_group_id')
        },
        async setDelete(data){
            if(await this.checkIfUsed('usergroup', data.item.user_group_id) == true){
                this.$notify({
                    type: 'error',
                    group: 'notification',
                    title: 'Error!',
                    text: "Unable to delete, this record is being used by other transactions."
                })
                return
            }
            this.showModalDelete=true
            this.user_group_id = data.item.user_group_id
        },
        setUpdate(data){
            this.validateRequiredFields()
            this.row = data.item
            this.fillEntityForm('usergroup', data.item.user_group_id)
            this.showModalEntry=true
            this.entryMode='Edit'
        },
        filterModules(module_group){
            return this.modules['modules'].filter(m => m.module_group == module_group);
        },
        filterModuleRights(module_id){
            return this.modules['rights'].filter(r => r.module_id == module_id);
        },
    },
    async created () {
        await this.fillTableList('usergroups');
        await this.$http.get('/api/modules', {
              headers: {
                      Authorization: 'Bearer ' + localStorage.getItem('token')
                  }
            })
            .then((response) => {
                const res = response.data
                this.modules = res.data
            })
            .catch(error => {
              if (!error.response) return
              console.log(error)
            })
    },
  }
</script>

