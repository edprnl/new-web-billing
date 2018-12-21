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
                                Charge List
                                <small class="font-italic">List of all registered charges.</small></span>
                        </h5>
                        
                        <b-row class="mb-2"> <!-- row button and search input -->
                            <b-col sm="4">
                                    <b-button variant="primary" @click="showModalEntry = true, entryMode='Add', clearFields('charge')">
                                            <i class="fa fa-plus-circle"></i> Create New Charge
                                    </b-button>
                            </b-col>

                            <b-col  sm="4">
                                <span></span>
                            </b-col>

                            <b-col  sm="4">
                                <b-form-input 
                                            v-model="filters.charges.criteria"
                                            type="text" 
                                            placeholder="Search">
                                </b-form-input>
                            </b-col>
                        </b-row> <!-- row button and search input -->
                        <b-row> <!-- row table -->
                            <b-col sm="12">
                                <b-table  
                                    :filter="filters.charges.criteria"
                                    :fields="tables.charges.fields"
                                    :items.sync="tables.charges.items"
                                    :current-page="paginations.charges.currentPage"
                                    :per-page="paginations.charges.perPage"
                                    striped hover small bordered show-empty
                                    
                                > <!-- table -->

                                <template slot="action" slot-scope="data"> <!-- action slot  :to="{path: 'categories/' + data.item.id } -->
                                    <b-btn :size="'sm'" variant="primary" @click="setUpdate(data)">
                                        <i class="fa fa-edit"></i>
                                    </b-btn>

                                    <b-btn :size="'sm'" variant="danger" @click="setDelete(data)">
                                        <i class="fa fa-trash"></i>
                                    </b-btn>
                                </template>

                                </b-table> <!-- table -->
                            </b-col>
                        </b-row> <!-- row table -->

                        <b-row >  <!-- Pagination -->
                                <b-col sm="12" class="my-1">
                                    <b-pagination size="sm" align="right" :total-rows="paginations.charges.totalRows" :per-page="paginations.charges.perPage" v-model="paginations.charges.currentPage"
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
            >
            
                <div slot="modal-title"> <!-- modal title -->
                    Charge Entry - {{entryMode}}
                </div> <!-- modal title -->

                <b-col lg=12> <!-- modal body -->
                    <b-form @keydown="resetFieldStates('charge')">
                        <b-form-group>
                            <label for="charge_code">* Charge Code</label>
                            <b-form-input
                                id="charge_code"
                                v-model="forms.charge.fields.charge_code"
                                :state="forms.charge.states.charge_code"
                                type="text"
                                placeholder="Charge Code">
                            </b-form-input>
                            <b-form-invalid-feedback>
                                <i class="fa fa-exclamation-triangle text-danger"></i>
                                <span v-for="itemError in forms.charge.errors.charge_code">
                                    {{itemError}}
                                </span>
                            </b-form-invalid-feedback>
                        </b-form-group>
                        <b-form-group>
                            <label>* Charge Desc</label>
                            <b-form-input
                                id="charge_desc"
                                v-model="forms.charge.fields.charge_desc"
                                :state="forms.charge.states.charge_desc"
                                type="text"
                                placeholder="Charge Description">
                            </b-form-input>
                            <b-form-invalid-feedback>
                                <i class="fa fa-exclamation-triangle text-danger"></i>
                                <span v-for="itemError in forms.charge.errors.charge_desc">
                                    {{itemError}}
                                </span>
                            </b-form-invalid-feedback>
                        </b-form-group>
                        <b-form-group>
                            <label>Account Title</label>
                            <select2
                                id="account_title"
                                :allowClear="false"
                                :placeholder="'Select Account Title'"
                                v-model="forms.charge.fields.account_id"
                                :state="forms.charge.states.account_id"
                            >
                                <option v-for="account in options.accounts.items" :key="account.account_id" :value="account.account_id">{{account.account_title}}</option>
                            </select2>
                            <b-form-invalid-feedback>
                                <i class="fa fa-exclamation-triangle text-danger"></i>
                                <span v-for="itemError in forms.charge.errors.account_id">
                                    {{itemError}}
                                </span>
                            </b-form-invalid-feedback>
                        </b-form-group>
                    </b-form>
                </b-col> <!-- modal body -->

                <div slot="modal-footer"><!-- modal footer buttons -->
                    <b-button :disabled="forms.charge.isSaving" variant="primary" @click="onChargeEntry">
                        <icon v-if="forms.charge.isSaving" name="sync" spin></icon>
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
                    Delete Charge
                </div>
                <b-col lg=12>
                    Are you sure you want to delete this charge?
                </b-col>
                <div slot="modal-footer">
                    <b-button :disabled="forms.charge.isSaving" variant="primary" @click="onChargeDelete">
                        <icon v-if="forms.charge.isSaving" name="sync" spin></icon>
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
    name: 'charges',
    data () {
      return {
        entryMode: 'Add',
        showModalEntry: false, //if true show modal
        showModalDelete: false,
        options: {
            accounts: {
                items: []
            },
        },
        forms:{
            charge : {
                isSaving: false,
                fields: {
                    charge_id: null,
                    charge_code: null,
                    charge_desc: null,
                    account_id: null
                },
                states: {
                    charge_code: null,
                    charge_desc: null,
                    account_id: null
                },
                errors: {
                    charge_code: null,
                    charge_desc: null,
                    account_id: null
                }
            }
        },
        tables: {
          charges: {
                fields: [
                {
                    key: 'charge_code',
                    label: 'Code',
                    thStyle: {width: '150px'}
                },
                {
                    key: 'charge_desc',
                    label: 'Charge Name'
                },
                {
                    key: 'account_title',
                    label: 'Account Title'
                },
                {
                    
                    key: 'action',
                    label: '',
                    thStyle: {width: '75px'}
                },
                ],
                items: []
            }
        },
        filters: {
          charges: {
            criteria: null
          }
        },
        paginations: {
          charges: {
            totalRows: 0,
            currentPage: 1,
            perPage: 10
          }
        },
        charge_id: null,
        row: []
      }
    },
    methods:{
        onChargeEntry () {
            if(this.entryMode == 'Add'){
                
                this.createEntity('charge', true, 'charges')
            }
            else{
                this.updateEntity('charge', 'charge_id', true, this.row)
            }
        },
        onChargeDelete(){
            this.deleteEntity('charge', this.charge_id, true, 'charges', 'charge_id')
        },
        async setDelete(data){
            if(await this.checkIfUsed('charge', data.item.charge_id) == true){
                this.$notify({
                    type: 'error',
                    group: 'notification',
                    title: 'Error!',
                    text: "Unable to delete, this record is being used by other transactions."
                })
                return
            }
            this.charge_id = data.item.charge_id
            this.showModalDelete = true
        },
        setUpdate(data){
            this.row = data.item
            this.resetFieldStates('charge')
            this.fillEntityForm('charge', data.item.charge_id)
            this.showModalEntry=true
            this.entryMode='Edit'
        }
    },
    computed: {

    },
    created () {
      this.fillTableList('charges')
      this.fillOptionsList('accounts')
    }
  }
</script>

