<template>
    <div><!-- main container -->
        <notifications group="notification" />
        <div class="animated fadeIn"> <!-- main div -->
            <b-row> <!-- main row -->
                <b-col sm="12">
                    <b-card > <!-- main card -->
                        <h5 slot="header">  <!-- table header -->
                            <span class="text-primary">
                                <i class="fa fa-bars"></i> 
                                Contract Type List
                                <small class="bfont-italic">List of all registered contract types.</small></span>
                        </h5>
                        
                        <b-row class="mb-2"> <!-- row button and search input -->
                            <b-col sm="4">
                                    <b-button v-if="checkRights('6-22')" variant="primary" @click="showModalEntry = true, entryMode='Add', clearFields('contracttype')">
                                            <i class="fa fa-plus-circle"></i> Create New Contract Type
                                    </b-button>
                            </b-col>

                            <b-col  sm="4">
                                <span></span>
                            </b-col>

                            <b-col  sm="4">
                                <b-form-input 
                                            v-model="filters.contracttypes.criteria"
                                            type="text" 
                                            placeholder="Search">
                                </b-form-input>
                            </b-col>
                        </b-row> <!-- row button and search input -->
                        <b-row> <!-- row table -->
                            <b-col sm="12">
                                <b-table 
                                    v-if="checkAction"
                                    responsive
                                    :filter="filters.contracttypes.criteria"
                                    :fields="tables.contracttypes.fields"
                                    :items.sync="tables.contracttypes.items"
                                    :current-page="paginations.contracttypes.currentPage"
                                    :per-page="paginations.contracttypes.perPage"
                                    @filtered="onFiltered($event,'contracttypes')"
                                    striped hover small bordered show-empty
                                    
                                > <!-- table -->

                                <template slot="action" slot-scope="data"> <!-- action slot  :to="{path: 'categories/' + data.item.id } -->
                                    <b-btn v-if="checkRights('6-23')" :size="'sm'" variant="primary" @click="setUpdate(data)">
                                        <i class="fa fa-edit"></i>
                                    </b-btn>

                                    <b-btn v-if="checkRights('6-24')" :size="'sm'" variant="danger" @click="setDelete(data)">
                                        <i class="fa fa-trash"></i>
                                    </b-btn>
                                </template>

                                </b-table> <!-- table -->
                            </b-col>
                        </b-row> <!-- row table -->

                        <b-row >  <!-- Pagination -->
                                <b-col sm="12" class="my-1">
                                    <b-pagination size="sm" align="right" :total-rows="paginations.contracttypes.totalRows" :per-page="paginations.contracttypes.perPage" v-model="paginations.contracttypes.currentPage"
                                     class="my-0" />
                                </b-col>
                        </b-row> <!-- Pagination -->
                        
                    </b-card><!-- main card -->
                </b-col>
            </b-row> <!-- main row -->

        </div><!-- main div -->

        <div> <!-- modal div -->
            <b-modal 
                v-model="showModalEntry"
                :noCloseOnEsc="true"
                :noCloseOnBackdrop="true"
                @shown="focusElement('contract_type_code')"
            >
                
                <div slot="modal-title"> <!-- modal title -->
                    Contract Type Entry - {{entryMode}}
                </div> <!-- modal title -->

                <b-col lg=12> <!-- modal body -->
                    <b-form @keydown="resetFieldStates('contracttype')" autocomplete="off">
                        <b-form-group>
                            <label for="contract_type_code">* Contract Type Code</label>
                            <b-form-input
                                ref="contract_type_code"
                                id="contract_type_code"
                                v-model="forms.contracttype.fields.contract_type_code"
                                type="text"
                                placeholder="Contract Type Code">
                            </b-form-input>
                        </b-form-group>
                        <b-form-group>
                            <label>* Contract Type Desc</label>
                            <b-form-input
                                ref="contract_type_desc"
                                id="contract_type_desc"
                                v-model="forms.contracttype.fields.contract_type_desc"
                                type="text"
                                placeholder="Contract Type Description">
                            </b-form-input>
                        </b-form-group>
                    </b-form>
                </b-col> <!-- modal body -->

                <div slot="modal-footer"><!-- modal footer buttons -->
                    <b-button :disabled="forms.contracttype.isSaving" variant="primary" @click="onContractTypeEntry">
                        <icon v-if="forms.contracttype.isSaving" name="sync" spin></icon>
                        <i class="fa fa-check"></i>
                        Save
                    </b-button>
                    <b-button variant="secondary" @click="showModalEntry=false">Close</b-button>
                </div> <!-- modal footer buttons -->

            </b-modal>
            <b-modal 
                v-model="showModalDelete"
                :noCloseOnEsc="true"
                :noCloseOnBackdrop="true"
            >
                <div slot="modal-title">
                    Delete Contract Type
                </div>
                <b-col lg=12>
                    Are you sure you want to delete this contract type?
                </b-col>
                <div slot="modal-footer">
                    <b-button :disabled="forms.contracttype.isSaving" variant="primary" @click="onContractTypeDelete">
                        <icon v-if="forms.contracttype.isSaving" name="sync" spin></icon>
                        <i class="fa fa-check"></i>
                        OK
                    </b-button>
                    <b-button variant="secondary" @click="showModalDelete=false">Close</b-button>            
                </div>
            </b-modal>
        </div> <!-- modal div -->

    </div> <!-- main container -->

   
</template>

<script>
export default {
    name: 'contracttypes',
    data () {
      return {
        entryMode: 'Add',
        showModalEntry: false, //if true show modal
        showModalDelete: false,
        forms:{
            contracttype : {
                isSaving: false,
                fields: {
                    contract_type_id: null,
                    contract_type_code: null,
                    contract_type_desc: null
                }
            }
        },
        tables: {
          contracttypes: {
                fields: [
                {
                    key: 'contract_type_code',
                    label: 'Code',
                    thStyle: {width: '150px'},
                    tdClass: 'align-middle',
                    sortable: true
                },
                {
                    key: 'contract_type_desc',
                    label: 'Location Name',
                    tdClass: 'align-middle',
                    sortable: true
                },
                {
                    
                    key: 'action',
                    label: '',
                    thStyle: {width: '75px'},
                    tdClass: 'text-center',
                },
                ],
                items: []
            }
        },
        filters: {
          contracttypes: {
            criteria: null
          }
        },
        paginations: {
          contracttypes: {
            totalRows: 0,
            currentPage: 1,
            perPage: 10
          }
        },
        contract_type_id: null,
        row: []
      }
    },
    methods:{
        onContractTypeEntry () {
            if(this.entryMode == 'Add'){
                this.createEntity('contracttype', true, 'contracttypes')
            }
            else{
                this.updateEntity('contracttype', 'contract_type_id', true, this.row)
            }
        },
        onContractTypeDelete(){
            this.deleteEntity('contracttype', this.contract_type_id, true, 'contracttypes', 'contract_type_id')
        },
        async setDelete(data){
            if(await this.checkIfUsed('contracttype', data.item.contract_type_id) == true){
                this.$notify({
                    type: 'error',
                    group: 'notification',
                    title: 'Error!',
                    text: "Unable to delete, this record is being used by other transactions."
                })
                return
            }
            this.contract_type_id = data.item.contract_type_id
            this.showModalDelete = true
        },
        setUpdate(data){
            this.row = data.item
            this.fillEntityForm('contracttype', data.item.contract_type_id)
            this.showModalEntry=true
            this.entryMode='Edit'

        }
    },
    computed: {
        checkAction(){
            if(this.$store.state.rights.length > 0){
                if((this.checkRights('6-23') || this.checkRights('6-24')) == false){
                    this.tables.contracttypes.fields.pop()
                }
            }
            return true
        }
    },
    created () {
      this.fillTableList('contracttypes')
      //console.log(this.tables.contracttypes.items);
    }
  }
</script>

