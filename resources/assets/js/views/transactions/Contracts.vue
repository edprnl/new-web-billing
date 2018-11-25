<template>
    <!--<b-animated fade-in>  main container -->
    <div>
        <notifications group="notification" />
        <div v-show="showEntry === false" class="animated fadeIn">
            <b-row>
                <b-col sm="12">
                    <b-card >
                        <h5 slot="header">
                            <span class="text-primary">
                                <i class="fa fa-bars"></i> 
                                Contract List
                                <small class="font-italic">List of all registered contracts.</small></span>
                        </h5>
                        <b-row class="mb-2">
                            <b-col sm="4">
                                    <b-button variant="primary" @click="showEntry = true, entryMode='Add'">
                                            <i class="fa fa-plus-circle"></i> Create New Contract
                                    </b-button>
                            </b-col>

                            <b-col  sm="4">
                                <span></span>
                            </b-col>

                            <b-col  sm="4">
                                <b-form-input 
                                            v-model="filters.contracts.criteria"
                                            type="text" 
                                            placeholder="Search">
                                </b-form-input>
                            </b-col>
                        </b-row>

                        <b-row>
                            <b-col sm="12">
                                <b-table 
                                    :filter="filters.contracts.criteria"
                                    :fields="tables.contracts.fields"
                                    :items.sync="tables.contracts.items"
                                    :current-page="paginations.contracts.currentPage"
                                    :per-page="paginations.contracts.perPage"
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
                                            :total-rows="paginations.contracts.totalRows"
                                            :per-page="paginations.contracts.perPage"
                                            v-model="paginations.contracts.currentPage" />
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
                                Contract Entry - {{entryMode}}
                            </span>
                        </h5>
                        <b-row>
                            <b-col sm="12">
                                <b-card no-body>
  <b-tabs v-model="tabIndex">
      <b-tab title="Tab 1" >
        Tab Contents 1
      </b-tab>
      <b-tab title="Tab 2" >
        Tab Contents 2
      </b-tab>
      <b-tab title="Tab 3">
        Tab Contents 3
      </b-tab>
    </b-tabs>
    </b-card>
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
    name: 'categories',
    data () {
        return {
            tabIndex: 0,
            entryMode: 'Add',
            showEntry: false, //if true show entry
            showModalDelete: false,
            forms: {
                contract: {
                    isSaving: false,
                    fields: {
                        contract_id: null,
                        contract_no: null,
                        tenant_code: null,
                        tenant_id: null,
                        contract_signatory: null,
                        contract_billing_address: null,
                        department_id: null,
                        nature_of_business_id: null,
                        contract_approved_merch: null,
                        location_id: null,
                        contract_type_id: null,
                        category_id: null,
                        contract_terms: 1,
                        commencement_date: new Date(),
                        termination_date: new Date(),
                        start_billing_date: new Date(),
                        contract_floor_area: 0,
                        contract_fixed_rent: 0,
                        contract_discounted_rent: 0,
                        contract_escalation_percent: 0,
                        security_deposit: 0,
                        power_meter_deposit: 0,
                    },
                    states: {
                        contract_id: null,
                        contract_no: null,
                        tenant_code: null,
                        tenant_id: null,
                        contract_signatory: null,
                        contract_billing_address: null,
                        department_id: null,
                        nature_of_business_id: null,
                        contract_approved_merch: null,
                        location_id: null,
                        contract_type_id: null,
                        category_id: null,
                        contract_terms: null,
                        commencement_date: null,
                        termination_date: null,
                        start_billing_date: null,
                        contract_floor_area: null,
                        contract_fixed_rent: null,
                        contract_discounted_rent: null,
                        contract_escalation_percent: null,
                        security_deposit: null,
                        power_meter_deposit: null,
                    },
                    errors: {
                        contract_id: null,
                        contract_no: null,
                        tenant_code: null,
                        tenant_id: null,
                        contract_signatory: null,
                        contract_billing_address: null,
                        department_id: null,
                        nature_of_business_id: null,
                        contract_approved_merch: null,
                        location_id: null,
                        contract_type_id: null,
                        category_id: null,
                        contract_terms: null,
                        commencement_date: null,
                        termination_date: null,
                        start_billing_date: null,
                        contract_floor_area: null,
                        contract_fixed_rent: null,
                        contract_discounted_rent: null,
                        contract_escalation_percent: null,
                        security_deposit: null,
                        power_meter_deposit: null,
                    }
                }
            },
            tables: {
                contracts: {
                    fields:[
                        {
                            key: 'contract_id',
                        },
                        {
                            key:'contract_no',
                            label: 'Contract No'
                        },
                        {
                            key:'trade_name',
                            label: 'Trade Name'
                        },
                        {
                            key:'department_desc',
                            label:'Department'
                        },
                        {
                            key:'location_desc',
                            label:'Location'
                        },
                        {
                            key:'contract_type_desc',
                            label:'Contract Type'
                        },
                        {
                            key:'category_desc',
                            label:'Category'
                        },
                        {
                            key:'action',
                            label:'Action'
                        }
                    ],
                    items: []
                }
            },
            filters: {
                contracts: {
                    criteria: null
                }
            },
            paginations: {
                contracts: {
                    totalRows: 0,
                    currentPage: 1,
                    perPage: 10
                }
            },
            datarow: []
        }
    },
    methods:{
        onCategoryEntry () {
            if(this.entryMode == 'Add'){
                this.createEntity('category', true, 'categories')
            }
            else{
                this.updateEntity('category', true, this.datarow)
            }
        
            //this.showModalEntry=false
        },
        setUpdate(data){
            this.fillEntityForm('category', data.item.category_id)
            this.showModalEntry=true
            this.entryMode='Edit'
            this.datarow=data.item.category_id
            // this.tables.categories.items.filter(function(elem){
            //     if(elem.category_id == 42) return elem
            // })
            // console.log(data.index)
        }
    },
    created () {
        this.fillTableList('contracts');
    },
  }
</script>


</script>