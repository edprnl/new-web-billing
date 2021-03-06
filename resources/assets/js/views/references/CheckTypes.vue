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
                                Check Type List
                                <small class="font-italic">List of all registered check types.</small></span>
                        </h5>
                        <b-row class="mb-2">
                            <b-col sm="4">
                                    <b-button v-if="checkRights('7-26')" variant="primary" @click="showModalEntry = true, entryMode='Add', clearFields('check_type')">
                                            <i class="fa fa-plus-circle"></i> Create New Check Type
                                    </b-button>
                            </b-col>

                            <b-col  sm="4">
                                <span></span>
                            </b-col>

                            <b-col  sm="4">
                                <b-form-input 
                                            v-model="filters.check_types.criteria"
                                            type="text" 
                                            placeholder="Search">
                                </b-form-input>
                            </b-col>
                        </b-row>

                        <b-row>
                            <b-col sm="12">
                                <b-table 
                                    v-if="checkAction"
                                    responsive
                                    :filter="filters.check_types.criteria"
                                    :fields="tables.check_types.fields"
                                    :items.sync="tables.check_types.items"
                                    :current-page="paginations.check_types.currentPage"
                                    :per-page="paginations.check_types.perPage"
                                    @filtered="onFiltered($event,'check_types')"
                                    striped hover small bordered show-empty
                                >
                                    <template slot="action" slot-scope="data">
                                        <b-btn :size="'sm'" variant="primary" @click="setUpdate(data)">
                                            <i class="fa fa-edit"></i>
                                        </b-btn>

                                        <b-btn :size="'sm'" variant="danger" @click="setDelete(data)">
                                            <i class="fa fa-trash"></i>
                                        </b-btn>
                                    </template>
                                    
                                </b-table>

                                <b-pagination
                                            :align="'right'"
                                            :total-rows="paginations.check_types.totalRows"
                                            :per-page="paginations.check_types.perPage"
                                            v-model="paginations.check_types.currentPage" />
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
            @shown="focusElement('check_type_code')"
        >
            <div slot="modal-title">
                Check Type Entry - {{entryMode}}
            </div>
            <b-col lg=12>
                <b-form @keydown="resetFieldStates('check_type')" autocomplete="off">
                    <b-form-group>
                        <label for="check_type_code">* Check Type Code</label>
                        <b-form-input
                            ref="check_type_code"
                            id="check_type_code"
                            v-model="forms.check_type.fields.check_type_code"
                            type="text"
                            placeholder="Check Type Code">
                        </b-form-input>
                    </b-form-group>
                    <b-form-group>
                        <label>* Check Type Desc</label>
                        <b-form-input
                            ref="check_type_desc"
                            id="check_type_desc"
                            v-model="forms.check_type.fields.check_type_desc"
                            type="text"
                            placeholder="Check Type Description">
                        </b-form-input>
                    </b-form-group>
                </b-form>
            </b-col>
            <div slot="modal-footer">
                <b-button :disabled="forms.check_type.isSaving" variant="primary" @click="onCheckTypeEntry">
                    <icon v-if="forms.check_type.isSaving" name="sync" spin></icon>
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
                Delete Check Type
            </div>
            <b-col lg=12>
                Are you sure you want to delete this check type?
            </b-col>
            <div slot="modal-footer">
                <b-button :disabled="forms.check_type.isSaving" variant="primary" @click="onCheckTypeDelete">
                    <icon v-if="forms.check_type.isSaving" name="sync" spin></icon>
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
    name: 'check_types',
    data () {
        return {
            entryMode: 'Add',
            showModalEntry: false, //if true show modal
            showModalDelete: false,
            forms: {
                check_type : {
                    isSaving: false,
                    fields: {
                        check_type_id: null,
                        check_type_code: null,
                        check_type_desc: null
                    }
                }
            },
            tables: {
                check_types: {
                    fields:[
                        {
                            key:'check_type_code',
                            label: 'Check Type Code',
                            thStyle: {width: '150px'},
                            tdClass: 'align-middle',
                            sortable: true
                        },
                        {
                            key:'check_type_desc',
                            label: 'Description',
                            tdClass: 'align-middle',
                            sortable: true
                        },
                        {
                            key:'action',
                            label:'',
                            thStyle: {width: '75px'},
                            tdClass: 'text-center',
                        }
                    ],
                    items: []
                }
            },
            filters: {
                check_types: {
                    criteria: null
                }
            },
            paginations: {
                check_types: {
                    totalRows: 0,
                    currentPage: 1,
                    perPage: 10
                }
            },
            check_type_id: null,
            row: []
        }
    },
    methods:{
        onCheckTypeEntry () {
            if(this.entryMode == 'Add'){
                
                this.createEntity('check_type', true, 'check_types')
            }
            else{
                this.updateEntity('check_type', 'check_type_id', true, this.row)
            }
        },
        onCheckTypeDelete(){
            this.deleteEntity('check_type', this.check_type_id, true, 'check_types', 'check_type_id')
        },
        async setDelete(data){
            if(await this.checkIfUsed('check_type', data.item.check_type_id) == true){
                this.$notify({
                    type: 'error',
                    group: 'notification',
                    title: 'Error!',
                    text: "Unable to delete, this record is being used by other transactions."
                })
                return
            }
            this.check_type_id = data.item.check_type_id
            this.showModalDelete = true
        },
        setUpdate(data){
            this.row = data.item
            this.fillEntityForm('check_type', data.item.check_type_id)
            this.showModalEntry=true
            this.entryMode='Edit'

        }
    },
    computed: {
        checkAction(){
            if(this.$store.state.rights.length > 0){
                if((this.checkRights('7-27') || this.checkRights('7-28')) == false){
                    this.tables.check_types.fields.pop()
                }
            }
            return true
        }
    },
    created () {
        this.fillTableList('check_types');
    },
  }
</script>

