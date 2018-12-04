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
                                    <b-button variant="primary" @click="resetFieldStates('tenant'),clearFields('tenant'),entryMode = 'Add', showEntry = true">
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
                                <b-table 
                                    :filter="filters.tenants.criteria"
                                    :fields="tables.tenants.fields"
                                    :items.sync="tables.tenants.items"
                                    :current-page="paginations.tenants.currentPage"
                                    :per-page="paginations.tenants.perPage"
                                    striped hover small bordered show-empty
                                >
                                    <template slot="action" slot-scope="data">
                                        <b-btn :size="'sm'" variant="primary" @click="setUpdate(data)">
                                            <i class="fa fa-edit"></i>
                                        </b-btn>

                                        <b-btn :size="'sm'" variant="danger" @click="onItemDelete(data)">
                                            <i class="fa fa-trash"></i>
                                        </b-btn>
                                    </template>
                                    
                                </b-table>

                                <b-pagination
                                            :align="'right'"
                                            :total-rows="paginations.tenants.totalRows"
                                            :per-page="paginations.tenants.perPage"
                                            v-model="paginations.tenants.criteria" />
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
                        <form @keydown="forms.tenant.errors[$event.target.name] = null, forms.tenant.states[$event.target.name] = null">
                            <b-row>
                                <b-col sm="4">
                                    <div class="border border-dark text-center">
                                        COMPANY INFORMATION
                                    </div>
                                    <br>
                                    <b-form-group>
                                        <label>Tenant Code</label>
                                        <b-form-input
                                            v-model="forms.tenant.fields.tenant_code"
                                            type="text"
                                            readonly
                                            placeholder="Auto">
                                        </b-form-input>
                                    </b-form-group>
                                    <b-form-group>
                                        <label>* Trade Name</label>
                                        <b-form-input
                                            name="trade_name"
                                            v-model="forms.tenant.fields.trade_name"
                                            :state="forms.tenant.states.trade_name"
                                            type="text"
                                            placeholder="Trade Name">
                                        </b-form-input>
                                        <b-form-invalid-feedback>
                                            <i class="fa fa-exclamation-triangle text-danger"></i>
                                            <span v-for="itemError in forms.tenant.errors.trade_name">
                                                {{itemError}}
                                            </span>
                                        </b-form-invalid-feedback>
                                    </b-form-group>
                                    <b-form-group>
                                        <label>* Company Name</label>
                                        <b-form-input
                                            name="company_name"
                                            v-model="forms.tenant.fields.company_name"
                                            :state="forms.tenant.states.company_name"
                                            type="text"
                                            placeholder="Company Name">
                                        </b-form-input>
                                        <b-form-invalid-feedback>
                                            <i class="fa fa-exclamation-triangle text-danger"></i>
                                            <span v-for="itemError in forms.tenant.errors.company_name">
                                                {{itemError}}
                                            </span>
                                        </b-form-invalid-feedback>
                                    </b-form-group>
                                    <b-form-group>
                                        <label>* Business Concept</label>
                                        <b-form-input
                                            name="business_concept"
                                            v-model="forms.tenant.fields.business_concept"
                                            :state="forms.tenant.states.business_concept"
                                            type="text"
                                            placeholder="Business Concept">
                                        </b-form-input>
                                        <b-form-invalid-feedback>
                                            <i class="fa fa-exclamation-triangle text-danger"></i>
                                            <span v-for="itemError in forms.tenant.errors.business_concept">
                                                {{itemError}}
                                            </span>
                                        </b-form-invalid-feedback>
                                    </b-form-group>
                                    <b-form-group>
                                        <label>* Head Office Address</label>
                                        <b-form-textarea
                                            name="head_office_address"
                                            v-model="forms.tenant.fields.head_office_address"
                                            :state="forms.tenant.states.head_office_address"
                                            type="text"
                                            :rows="2"
                                            placeholder="Head Office Address">
                                        </b-form-textarea>
                                        <b-form-invalid-feedback>
                                            <i class="fa fa-exclamation-triangle text-danger"></i>
                                            <span v-for="itemError in forms.tenant.errors.head_office_address">
                                                {{itemError}}
                                            </span>
                                        </b-form-invalid-feedback>
                                    </b-form-group>
                                    <b-form-group>
                                        <label>* Billing Address</label>
                                        <b-form-textarea
                                            name="billing_address"
                                            v-model="forms.tenant.fields.billing_address"
                                            :state="forms.tenant.states.billing_address"
                                            type="text"
                                            :rows="2"
                                            placeholder="Billing Address">
                                        </b-form-textarea>
                                        <b-form-invalid-feedback>
                                            <i class="fa fa-exclamation-triangle text-danger"></i>
                                            <span v-for="itemError in forms.tenant.errors.billing_address">
                                                {{itemError}}
                                            </span>
                                        </b-form-invalid-feedback>
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
                                            name="contact_person"
                                            v-model="forms.tenant.fields.contact_person"
                                            :state="forms.tenant.states.contact_person"
                                            type="text"
                                            placeholder="Contact Person">
                                        </b-form-input>
                                        <b-form-invalid-feedback>
                                            <i class="fa fa-exclamation-triangle text-danger"></i>
                                            <span v-for="itemError in forms.tenant.errors.contact_person">
                                                {{itemError}}
                                            </span>
                                        </b-form-invalid-feedback>
                                    </b-form-group>
                                    <b-form-group>
                                        <label>* Designation</label>
                                        <b-form-input
                                            name="designation"
                                            v-model="forms.tenant.fields.designation"
                                            :state="forms.tenant.states.designation"
                                            type="text"
                                            placeholder="Designation">
                                        </b-form-input>
                                        <b-form-invalid-feedback>
                                            <i class="fa fa-exclamation-triangle text-danger"></i>
                                            <span v-for="itemError in forms.tenant.errors.designation">
                                                {{itemError}}
                                            </span>
                                        </b-form-invalid-feedback>
                                    </b-form-group>
                                    <b-form-group>
                                        <label>* Contact Number</label>
                                        <b-form-input
                                            name="contact_number"
                                            v-model="forms.tenant.fields.contact_number"
                                            :state="forms.tenant.states.contact_number"
                                            type="text"
                                            placeholder="Contact Person">
                                        </b-form-input>
                                        <b-form-invalid-feedback>
                                            <i class="fa fa-exclamation-triangle text-danger"></i>
                                            <span v-for="itemError in forms.tenant.errors.contact_number">
                                                {{itemError}}
                                            </span>
                                        </b-form-invalid-feedback>
                                    </b-form-group>
                                    <b-form-group>
                                        <label>* Email Address</label>
                                        <b-form-input
                                            name="email_address"
                                            v-model="forms.tenant.fields.email_address"
                                            :state="forms.tenant.states.email_address"
                                            type="text"
                                            placeholder="Email Address">
                                        </b-form-input>
                                        <b-form-invalid-feedback>
                                            <i class="fa fa-exclamation-triangle text-danger"></i>
                                            <span v-for="itemError in forms.tenant.errors.email_address">
                                                {{itemError}}
                                            </span>
                                        </b-form-invalid-feedback>
                                    </b-form-group>
                                    <b-form-group>
                                        <label>* TIN</label>
                                        <b-form-input
                                            name="tin_number"
                                            v-model="forms.tenant.fields.tin_number"
                                            :state="forms.tenant.states.tin_number"
                                            type="text"
                                            placeholder="TIN">
                                        </b-form-input>
                                        <b-form-invalid-feedback>
                                            <i class="fa fa-exclamation-triangle text-danger"></i>
                                            <span v-for="itemError in forms.tenant.errors.tin_number">
                                                {{itemError}}
                                            </span>
                                        </b-form-invalid-feedback>
                                    </b-form-group>
                                    <b-form-group>
                                        <label>Auto Penalty Previous Balance</label>
                                        <b-form-radio-group 
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
                                            v-model="forms.tenant.fields.business_permit"
                                            value="1"
                                            unchecked-value="0">
                                            Business Permits
                                        </b-form-checkbox>
                                    </b-form-group>
                                    <b-form-group>
                                        <b-form-checkbox
                                            v-model="forms.tenant.fields.tenant_information_sheet"
                                            value="1"
                                            unchecked-value="0">
                                            Tenant Information Sheet
                                        </b-form-checkbox>
                                    </b-form-group>
                                    <b-form-group>
                                        <b-form-checkbox
                                            v-model="forms.tenant.fields.valid_id"
                                            value="1"
                                            unchecked-value="0">
                                            Valid ID of the Signatory
                                        </b-form-checkbox>
                                    </b-form-group>
                                    <b-form-group>
                                        <b-form-checkbox
                                            v-model="forms.tenant.fields.tin_cor"
                                            value="1"
                                            unchecked-value="0">
                                            TIN/COR
                                        </b-form-checkbox>
                                    </b-form-group>
                                    <b-form-group>
                                        <b-form-checkbox
                                            v-model="forms.tenant.fields.dti_sec"
                                            value="1"
                                            unchecked-value="0">
                                            DTI Permit/SEC Registration
                                        </b-form-checkbox>
                                    </b-form-group>
                                    <b-form-group>
                                        <b-form-checkbox
                                            v-model="forms.tenant.fields.notarized_contract"
                                            value="1"
                                            unchecked-value="0">
                                            Notarized Contract of Lease
                                        </b-form-checkbox>
                                    </b-form-group>
                                    <b-form-group>
                                        <b-form-checkbox
                                            v-model="forms.tenant.fields.proof_of_billing"
                                            value="1"
                                            unchecked-value="0">
                                            Proof of Billing
                                        </b-form-checkbox>
                                    </b-form-group>
                                    <b-form-group>
                                        <b-form-checkbox
                                            @change="forms.tenant.fields.others_specify=null"
                                            v-model="forms.tenant.fields.others"
                                            value="1"
                                            unchecked-value="0">
                                            Others (Please Specify)
                                        </b-form-checkbox>
                                    </b-form-group>
                                    <b-form-group>
                                        <b-form-textarea
                                            :disabled="forms.tenant.fields.others == 1 ? false : true"
                                            v-model="forms.tenant.fields.others_specify"
                                            type="text"
                                            :rows="2"
                                            placeholder="Specify">
                                        </b-form-textarea>
                                    </b-form-group>
                                </b-col>
                            </b-row>
                        </form>
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
                    fields: {
                        tenant_id: null,
                        tenant_code: null,
                        trade_name: null,
                        company_name: null,
                        business_concept: null,
                        head_office_address: null,
                        billing_address: null,
                        contact_person: null,
                        designation: null,
                        contact_number: null,
                        email_address: null,
                        tin_number: null,
                        is_auto: 0,
                        business_permit: null,
                        tenant_information_sheet: null,
                        valid_id: null,
                        tin_cor: null,
                        dti_sec: null,
                        notarized_contract: null,
                        proof_of_billing: null,
                        others: null,
                        others_specify: null
                    },
                    states: {
                        tenant_id: null,
                        tenant_code: null,
                        trade_name: null,
                        company_name: null,
                        business_concept: null,
                        head_office_address: null,
                        billing_address: null,
                        contact_person: null,
                        designation: null,
                        contact_number: null,
                        email_address: null,
                        tin_number: null,
                        is_auto: null,
                        business_permit: null,
                        tenant_information_sheet: null,
                        valid_id: null,
                        tin_cor: null,
                        dti_sec: null,
                        notarized_contract: null,
                        proof_of_billing: null,
                        others: null,
                        others_specify: null
                    },
                    errors: {
                        tenant_id: null,
                        tenant_code: null,
                        trade_name: null,
                        company_name: null,
                        business_concept: null,
                        head_office_address: null,
                        billing_address: null,
                        contact_person: null,
                        designation: null,
                        contact_number: null,
                        email_address: null,
                        tin_number: null,
                        is_auto: null,
                        business_permit: null,
                        tenant_information_sheet: null,
                        valid_id: null,
                        tin_cor: null,
                        dti_sec: null,
                        notarized_contract: null,
                        proof_of_billing: null,
                        others: null,
                        others_specify: null
                    }
                }
            },
            tables: {
                tenants: {
                    fields:[
                        {
                            key:'tenant_code',
                            label: 'Tenant Code'
                        },
                        {
                            key:'trade_name',
                            label: 'Trade Name'
                        },
                        {
                            key:'company_name',
                            label: 'Company Name'
                        },
                        {
                            key:'head_office_address',
                            label: 'Address'
                        },
                        {
                            key:'contact_person',
                            label: 'Contact Person'
                        },
                        {
                            key:'designation',
                            label: 'Designation'
                        },
                        {
                            key:'business_concept',
                            label: 'Business Concept'
                        },
                        {
                            key: 'action',
                            label: 'Action'
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
            }
        }
    },
    methods:{
        onTenantEntry(){
            if(this.entryMode == 'Add'){
                this.createEntity('tenant', false, 'tenants')
            }
            else{
                this.updateEntity('tenant', 'tenant_id', false, 'tenants')
            }
        },
        setUpdate(data){
            this.resetFieldStates('tenant')
            this.fillEntityForm('tenant', data.item.tenant_id)
            this.showEntry=true
            this.entryMode='Edit'
        }
    },
    created () {
      this.fillTableList('tenants');
    }
  }
</script>

