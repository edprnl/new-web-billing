<template>
    <div>
        <notifications group="notification" />
        <div v-show="showEntry === false" class="animated fadeIn">
            <b-row>
                <b-col sm="12">
                    <b-card>
                        <h5 slot="header">
                            <span class="text-primary">
                                <i class="fa fa-bars"></i> 
                                Tenant List
                                <small class="font-italic">List of all registered tenants.</small></span>
                        </h5>
                        <b-row class="mb-2">
                            <b-col  sm="4">
                                    <b-button v-if="checkRights('1-2')" variant="primary" @click="clearFields('tenant'), entryMode = 'Add', clearDocuments(), showEntry = true">
                                            <i class="fa fa-plus-circle"></i> Create New Tenant
                                    </b-button>
                            </b-col>

                            <b-col  sm="4">
                                <span></span>
                            </b-col>

                            <b-col  sm="4">
                                <b-form-input 
                                            v-model="filters.tenants.criteria" 
                                            type="text" 
                                            placeholder="Search">
                                </b-form-input>
                            </b-col>
                        </b-row>
                        
                        <b-row>
                            <b-col sm="12">
                                <b-table v-if="checkAction"
                                    responsive
                                    :filter="filters.tenants.criteria"
                                    :fields="tables.tenants.fields"
                                    :items.sync="tables.tenants.items"
                                    :current-page="paginations.tenants.currentPage"
                                    :per-page="paginations.tenants.perPage"
                                    striped small bordered show-empty
                                >
                                    <template slot="row_data" slot-scope="row">
                                        <b-btn :size="'sm'" variant="success" @click.stop="row.toggleDetails(), tenantFiles(row)">
                                            <i :class="row.detailsShowing ? 'fa fa-minus-circle' : 'fa fa-plus-circle'"></i>
                                        </b-btn>
                                    </template>
                                    <template slot="row-details" slot-scope="row">
                                        <b-row>
                                            <b-col lg="2"></b-col>
                                            <b-col lg="8">
                                                <b-row class="font-weight-bold">
                                                    <b-col lg="6">
                                                        <p>Tenant Code : {{row.item.tenant_code}}</p>
                                                        <p>Trade Name : {{row.item.trade_name}}</p>
                                                        <p>Company Name : {{row.item.company_name}}</p>
                                                        <p>Space Code : {{row.item.space_code}}</p>
                                                        <p>Business Concept : {{row.item.business_concept}}</p>
                                                        <p>Head Office Address : {{row.item.head_office_address}}</p>
                                                        <p>Billing Address : {{row.item.billing_address}}</p>
                                                    </b-col>
                                                    <b-col lg="6">
                                                        <p>Contact Person : {{row.item.contact_person}}</p>
                                                        <p>Designation : {{row.item.designation}}</p>
                                                        <p>Contact Number : {{row.item.contact_number}}</p>
                                                        <p>Email Address : {{row.item.email_address}}</p>
                                                        <p>TIN : {{row.item.tin_number}}</p>
                                                    </b-col>
                                                </b-row>
                                                <b-form-file @change="fieldChange($event, row)" ref="file" plain style="display: none;"></b-form-file>
                                                <b-btn class="float-right mb-2" variant="primary" @click="$refs.file.$el.click()">
                                                    <i class="fa fa-file-o"></i>
                                                    Add File
                                                </b-btn>
                                                <table class="w-100 mb-2 responsive">
                                                    <thead>
                                                        <tr>
                                                            <th>Filename</th>
                                                            <th class="text-center" style="width:75px;">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr v-if="row.item.files.length == 0" >
                                                            <td colspan="2">No files found.</td>
                                                        </tr>
                                                        <tr v-for="files in row.item.files">
                                                            <td class="align-middle">{{ files.file_name }}</td>
                                                            <td class="text-center">
                                                                <b-btn size="sm" :href="files.file_path+'/'+files.file_name" download="" variant="primary"><i class="fa fa-download"></i></b-btn>
                                                                <b-btn @click="fileDelete(row, files)" size="sm" variant="danger"><i class="fa fa-trash"></i></b-btn>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </b-col>
                                            <b-col lg="2"></b-col>
                                        </b-row>
                                    </template>
                                    <template slot="action" slot-scope="data">
                                        <b-btn v-if="checkRights('1-3')" :size="'sm'" variant="primary" @click="setUpdate(data)">
                                            <i class="fa fa-edit"></i>
                                        </b-btn>

                                        <b-btn v-if="checkRights('1-4')" :size="'sm'" variant="danger" @click="setDelete(data)">
                                            <icon v-if="forms.tenant.isDeleting" name="sync" spin></icon>
                                            <i v-else class="fa fa-trash"></i>
                                        </b-btn>
                                    </template>
                                    
                                </b-table>

                                <b-pagination
                                            :align="'right'"
                                            :total-rows="paginations.tenants.totalRows"
                                            :per-page="paginations.tenants.perPage"
                                            v-model="paginations.tenants.currentPage" />
                            </b-col>
                        </b-row>

                    </b-card>
                </b-col>
            </b-row>
        </div>
        <div v-show="showEntry" class="animated fadeIn">
            <b-row>
                <b-col sm="12">
                    <b-card >
                        <h5 slot="header">
                            <span class="text-primary">
                                <i class="fa fa-bars"></i>
                                Tenant Entry - {{entryMode}}
                            </span>
                        </h5>
                        <b-form autocomplete="off">
                            <b-row>
                                <b-col sm="4">
                                    <div class="border border-dark text-center">
                                        COMPANY INFORMATION
                                    </div>
                                    <br>
                                    <b-form-group>
                                        <label>Tenant Code</label>
                                        <b-form-input
                                            id="tenant_code"
                                            v-model="forms.tenant.fields.tenant_code"
                                            type="text"
                                            readonly
                                            placeholder="Auto">
                                        </b-form-input>
                                    </b-form-group>
                                    <b-form-group>
                                        <label>* Trade Name</label>
                                        <b-form-input
                                            ref="trade_name"
                                            id="trade_name"
                                            v-model="forms.tenant.fields.trade_name"
                                            type="text"
                                            placeholder="Trade Name">
                                        </b-form-input>
                                    </b-form-group>
                                    <b-form-group>
                                        <label>* Company Name</label>
                                        <b-form-input
                                            ref="company_name"
                                            id="company_name"
                                            v-model="forms.tenant.fields.company_name"
                                            type="text"
                                            placeholder="Company Name">
                                        </b-form-input>
                                    </b-form-group>
                                    <b-form-group>
                                        <label>* Space Code</label>
                                        <b-form-input
                                            ref="space_code"
                                            id="space_code"
                                            v-model="forms.tenant.fields.space_code"
                                            type="text"
                                            placeholder="Space Code">
                                        </b-form-input>
                                    </b-form-group>
                                    <b-form-group>
                                        <label>* Business Concept</label>
                                        <b-form-input
                                            ref="business_concept"
                                            id="business_concept"
                                            v-model="forms.tenant.fields.business_concept"
                                            type="text"
                                            placeholder="Business Concept">
                                        </b-form-input>
                                    </b-form-group>
                                    <b-form-group>
                                        <label>* Head Office Address</label>
                                        <b-form-textarea
                                            ref="head_office_address"
                                            id="head_office_address"
                                            v-model="forms.tenant.fields.head_office_address"
                                            type="text"
                                            :rows="2"
                                            placeholder="Head Office Address">
                                        </b-form-textarea>
                                    </b-form-group>
                                    <b-form-group>
                                        <label>* Billing Address</label>
                                        <b-form-textarea
                                            ref="billing_address"
                                            id="billing_address"
                                            v-model="forms.tenant.fields.billing_address"
                                            type="text"
                                            :rows="2"
                                            placeholder="Billing Address">
                                        </b-form-textarea>
                                    </b-form-group>
                                </b-col>
                                <b-col sm="4">
                                    <div class="border border-dark text-center">
                                        CONTRACT SIGNATORY/CONTACT PERSON
                                    </div>
                                    <br>
                                    <b-form-group>
                                        <label>* Contact Person</label>
                                        <b-form-input
                                            ref="contact_person"
                                            id="contact_person"
                                            v-model="forms.tenant.fields.contact_person"
                                            type="text"
                                            placeholder="Contact Person">
                                        </b-form-input>
                                    </b-form-group>
                                    <b-form-group>
                                        <label>* Designation</label>
                                        <b-form-input
                                            ref="designation"
                                            id="designation"
                                            v-model="forms.tenant.fields.designation"
                                            type="text"
                                            placeholder="Designation">
                                        </b-form-input>
                                    </b-form-group>
                                    <b-form-group>
                                        <label>* Contact Number</label>
                                        <b-form-input
                                            ref="contact_number"
                                            id="contact_number"
                                            v-model="forms.tenant.fields.contact_number"
                                            type="text"
                                            placeholder="Contact Person">
                                        </b-form-input>
                                    </b-form-group>
                                    <b-form-group>
                                        <label>Email Address</label>
                                        <b-form-input
                                            ref="email_address"
                                            id="email_address"
                                            v-model="forms.tenant.fields.email_address"
                                            type="text"
                                            placeholder="Email Address">
                                        </b-form-input>
                                    </b-form-group>
                                    <b-form-group>
                                        <label>* TIN</label>
                                        <b-form-input
                                            ref="tin_number"
                                            id="tin_number"
                                            v-model="forms.tenant.fields.tin_number"
                                            type="text"
                                            placeholder="TIN">
                                        </b-form-input>
                                    </b-form-group>
                                    <b-form-group>
                                        <label>Auto Penalty Previous Balance</label>
                                        <b-form-radio-group 
                                            id="is_auto"
                                            v-model="forms.tenant.fields.is_auto">
                                            <b-form-radio value="1">On</b-form-radio>
                                            <b-form-radio value="0">Off</b-form-radio>
                                        </b-form-radio-group>
                                    </b-form-group>
                                </b-col>
                                <b-col sm="4">
                                    <div class="border border-dark text-center">
                                        DOCUMENTS
                                    </div>
                                    <br>
                                    <b-form-group>
                                        <b-form-checkbox
                                            id="business_permit"
                                            v-model="forms.tenant.fields.business_permit"
                                            value="1"
                                            unchecked-value="0">
                                            Business Permits
                                        </b-form-checkbox>
                                    </b-form-group>
                                    <b-form-group>
                                        <b-form-checkbox
                                            id="tenant_information_sheet"
                                            v-model="forms.tenant.fields.tenant_information_sheet"
                                            value="1"
                                            unchecked-value="0">
                                            Tenant Information Sheet
                                        </b-form-checkbox>
                                    </b-form-group>
                                    <b-form-group>
                                        <b-form-checkbox
                                            id="valid_id"
                                            v-model="forms.tenant.fields.valid_id"
                                            value="1"
                                            unchecked-value="0">
                                            Valid ID of the Signatory
                                        </b-form-checkbox>
                                    </b-form-group>
                                    <b-form-group>
                                        <b-form-checkbox
                                            id="tin_cor"
                                            v-model="forms.tenant.fields.tin_cor"
                                            value="1"
                                            unchecked-value="0">
                                            TIN/COR
                                        </b-form-checkbox>
                                    </b-form-group>
                                    <b-form-group>
                                        <b-form-checkbox
                                            id="dti_sec"
                                            v-model="forms.tenant.fields.dti_sec"
                                            value="1"
                                            unchecked-value="0">
                                            DTI Permit/SEC Registration
                                        </b-form-checkbox>
                                    </b-form-group>
                                    <b-form-group>
                                        <b-form-checkbox
                                            id="notarized_contract"
                                            v-model="forms.tenant.fields.notarized_contract"
                                            value="1"
                                            unchecked-value="0">
                                            Notarized Contract of Lease
                                        </b-form-checkbox>
                                    </b-form-group>
                                    <!-- <b-form-group>
                                        <b-form-checkbox
                                            id="proof_of_billing"
                                            v-model="forms.tenant.fields.proof_of_billing"
                                            value="1"
                                            unchecked-value="0">
                                            Proof of Billing
                                        </b-form-checkbox>
                                    </b-form-group> -->
                                    <b-form-group>
                                        <b-form-checkbox
                                            id="others"
                                            @change="forms.tenant.fields.others_specify=null"
                                            v-model="forms.tenant.fields.others"
                                            value="1"
                                            unchecked-value="0">
                                            Others (Please Specify)
                                        </b-form-checkbox>
                                    </b-form-group>
                                    <b-form-group>
                                        <b-form-textarea
                                            id="others_specify"
                                            :disabled="forms.tenant.fields.others == 1 ? false : true"
                                            v-model="forms.tenant.fields.others_specify"
                                            type="text"
                                            :rows="2"
                                            placeholder="Specify">
                                        </b-form-textarea>
                                    </b-form-group>
                                </b-col>
                            </b-row>
                        </b-form>
                        <b-row class="pull-right mt-2">
                            <b-col sm="12">
                                <b-button 
                                    :disabled="forms.tenant.isSaving" 
                                    variant="primary" 
                                    @click="onTenantEntry">
                                    <icon v-if="forms.tenant.isSaving" name="sync" spin></icon>
                                    <i class="fa fa-check"></i>
                                    Save
                                </b-button>
                                <b-button variant="secondary" @click="showEntry=false">Close</b-button>
                            </b-col>
                        </b-row>
                    </b-card>
                </b-col>
            </b-row>
        </div>
        <b-modal 
            v-model="showModalDelete"
            :noCloseOnEsc="true"
            :noCloseOnBackdrop="true"
        >
            <div slot="modal-title">
                Delete Tenant
            </div>
            <b-col lg=12>
                Are you sure you want to delete this tenant?
            </b-col>
            <div slot="modal-footer">
                <b-button :disabled="forms.tenant.isSaving" variant="primary" @click="onTenantDelete">
                    <icon v-if="forms.tenant.isSaving" name="sync" spin></icon>
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
    name: 'tenants',
    data () {
      return {
            entryMode: 'Add',
            showEntry: false, //if true show entry
            showModalDelete: false,
            forms: {
                tenant: {
                    isSaving: false,
                    isDeleting: false,
                    fields: {
                        tenant_id: null,
                        tenant_code: null,
                        trade_name: null,
                        company_name: null,
                        space_code: null,
                        business_concept: null,
                        head_office_address: null,
                        billing_address: null,
                        contact_person: null,
                        designation: null,
                        contact_number: null,
                        email_address: null,
                        tin_number: null,
                        is_auto: 0,
                        business_permit: 0,
                        tenant_information_sheet: 0,
                        valid_id: 0,
                        tin_cor: 0,
                        dti_sec: 0,
                        notarized_contract: 0,
                        proof_of_billing: 0,
                        others: 0,
                        others_specify: null
                    },
                }
            },
            tables: {
                tenants: {
                    fields:[
                        {
                            key:'row_data',
                            label: '',
                            tdClass: '',
                            thStyle: {width: '2%'}
                        },
                        {
                            key:'tenant_code',
                            label: 'Tenant Code',
                            thStyle: {width: '100px'},
                            tdClass: 'align-middle',
                            sortable:true
                        },
                        {
                            key:'trade_name',
                            label: 'Trade Name',
                            thStyle: {width: '13%'},
                            tdClass: 'align-middle',
                            sortable:true
                        },
                        {
                            key:'company_name',
                            label: 'Company Name',
                            thStyle: {width: '13%'},
                            tdClass: 'align-middle',
                        },
                        {
                            key:'head_office_address',
                            label: 'Address',
                            thStyle: {width: '15%'},
                            tdClass: 'align-middle',
                        },
                        {
                            key:'contact_person',
                            label: 'Contact Person',
                            thStyle: {width: '12%'},
                            tdClass: 'align-middle',
                        },
                        {
                            key:'designation',
                            label: 'Designation',
                            thStyle: {width: '12%'},
                            tdClass: 'align-middle',
                        },
                        {
                            key:'business_concept',
                            label: 'Business Concept',
                            thStyle: {width: '12%'},
                            tdClass: 'align-middle',
                        },
                        {
                            key: 'action',
                            label: 'Action',
                            tdClass: 'text-center',
                            thClass: 'text-center',
                            thStyle: {width: '75px'}
                        }
                    ],
                    items: []
                }
            },
            filters: {
                tenants: {
                    criteria: null
                }
            },
            paginations: {
                tenants: {
                    totalRows: 0,
                    currentPage: 1,
                    perPage: 10
                }
            },
            tenant_id: null,
            file: new FormData,
            row: []
        }
    },
    methods:{
        onTenantEntry(){
            if(this.entryMode == 'Add'){
                this.createEntity('tenant', false, 'tenants')
            }
            else{
                this.updateEntity('tenant', 'tenant_id', false, this.row)
            }
        },
        onTenantDelete(){
            this.deleteEntity('tenant', this.tenant_id, true, 'tenants', 'tenant_id')
        },
        async setDelete(data){
            if(await this.checkIfUsed('tenant', data.item.tenant_id) == true){
                this.$notify({
                    type: 'error',
                    group: 'notification',
                    title: 'Error!',
                    text: "Unable to delete, this record is being used by other transactions."
                })
                return
            }
            this.tenant_id = data.item.tenant_id
            this.showModalDelete = true
        },
        async setUpdate(data){
            this.row = data.item
            this.fillEntityForm('tenant', data.item.tenant_id)
            this.showEntry=true
            this.entryMode='Edit'
        },
        async tenantFiles(row){
            if(row.detailsShowing == true){
                return
            }
            var res = []
            await this.$http.get('/api/tenant_files/' + row.item.tenant_id, {
                headers: {
                      Authorization: 'Bearer ' + localStorage.getItem('token'),
                      'Content-Type' : 'multipart/form-data'
                  }
            })
            .then((response) => {
                row.item.files = response.data.data
            })
        },
        fieldChange(e, row){
            let attachment = e.target.files[0]
            let path = 'uploads/tenants'

            this.file.append('folder', row.item.tenant_id)
            this.file.append('file', attachment)
            this.file.append('path', path)

            this.$http.post('/api/fileupload', this.file, {
                headers: {
                      Authorization: 'Bearer ' + localStorage.getItem('token'),
                      'Content-Type' : 'multipart/form-data'
                  }
            })
            .then((response) => {
                if(response.data != null && response.data != ""){
                    row.item.files.unshift({file_name:response.data.name, file_path:response.data.path, file_id:response.data.id})
                }
            })
            .catch(error => {
              if (!error.response) return
              console.log(error)
            })
        },
        fileDelete(row, file){
            this.$http.post('/api/filedelete', {path: file.file_path+'/'+file.file_name, file_id: file.file_id}, {
                headers: {
                      Authorization: 'Bearer ' + localStorage.getItem('token')
                  }
            })
            .then((response) => {
                console.log(response)
                this.$delete(row.item.files, row.item.files.findIndex(f => f.file_id == file.file_id))
                this.$notify({
                    type: response.data.status,
                    group: 'notification',
                    title: response.data.title,
                    text: response.data.message
                })
            })
            .catch(error => {
              if (!error.response) return
              console.log(error)
            })
        },
        clearDocuments(){
            this.forms.tenant.fields.business_permit = 0
            this.forms.tenant.fields.tenant_information_sheet = 0
            this.forms.tenant.fields.valid_id = 0
            this.forms.tenant.fields.tin_cor = 0
            this.forms.tenant.fields.dti_sec = 0
            this.forms.tenant.fields.notarized_contract = 0
            this.forms.tenant.fields.proof_of_billing = 0
            this.forms.tenant.fields.others = 0
        }
    },
    created () {
        this.fillTableList('tenants');
    },
    watch: {
        showEntry: function (showEntry) {
            if(showEntry){
                let self = this
                Vue.nextTick(function(){
                    self.focusElement('trade_name')
                })
            }
        },
    },
    computed: {
        checkAction(){
            if(this.$store.state.rights.length > 0){
                if((this.checkRights('1-3') || this.checkRights('1-4')) == false){
                    this.tables.tenants.fields.pop()
                }
            }
            return true
        }
    }
  }
</script>

