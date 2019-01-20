<template>
    <div>
        <notifications group="notification" />
        <div v-show="showList === true" class="animated fadeIn">
            <b-row>
                <b-col sm="12">
                    <b-card >
                        <h5 slot="header">
                            <span class="text-primary">
                                <i class="fa fa-bars"></i> 
                                Adjustment List
                                <small>( {{ forms.period.fields.month_name + ' ' + forms.period.fields.app_year }} )</small>
                                <small class="font-italic">List of all adjustments.</small></span>
                        </h5>
                        <b-row class="mb-2">
                            <b-col sm="4">
                                    <b-button variant="primary" 
                                        @click="showModalEntry = true, entryMode='Add', clearFields('adjustment')">
                                            <i class="fa fa-plus-circle"></i> Create New Adjustment
                                    </b-button>
                            </b-col>

                            <b-col  sm="4">
                                <span></span>
                            </b-col>

                            <b-col  sm="4">
                                <b-form-input 
                                            v-model="filters.adjustments.criteria"
                                            type="text" 
                                            placeholder="Search">
                                </b-form-input>
                            </b-col>
                        </b-row>

                        <b-row>
                            <b-col sm="12">
                                <b-table 
                                    :filter="filters.adjustments.criteria"
                                    :fields="tables.adjustments.fields"
                                    :items.sync="tables.adjustments.items"
                                    :current-page="paginations.adjustments.currentPage"
                                    :per-page="paginations.adjustments.perPage"
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
                                            :total-rows="paginations.adjustments.totalRows"
                                            :per-page="paginations.adjustments.perPage"
                                            v-model="paginations.adjustments.currentPage" />
                            </b-col>
                        </b-row>

                    </b-card>
                </b-col>
            </b-row>
        </div>
        <b-modal
            v-model="showModalPeriod"
            :noCloseOnEsc="true"
            :noCloseOnBackdrop="true"
            @shown="focusElement('period_id')"
        >
            <div slot="modal-title">
                Billing Period
            </div>
            <b-row>
                <b-col lg=12>
                    <b-form-group>
                        <label>Billing Period</label>
                        <select2
                            ref="period_id"
                            :allowClear="false"
                            :placeholder="'Select Billing Period'"
                            v-model="forms.period.fields.period_id"
                            @input="getPeriodInfo"
                        >
                            <option v-for="period in options.periods.items" :key="period.period_id" :value="period.period_id">{{ moment(period.period_start_date, "MMMM DD, YYYY")}} - {{ moment(period.period_end_date, "MMMM DD, YYYY")}}</option>
                        </select2>
                    </b-form-group>
                </b-col>
            </b-row>
            <b-row>
                <b-col lg=12>
                    <b-form-group>
                        <label>Due Date</label>
                        <b-form-input 
                            v-model="forms.period.fields.period_due_date"
                            type="text" 
                            placeholder="Due Date"
                            readonly>
                        </b-form-input>
                    </b-form-group>
                </b-col>
            </b-row>
            <b-row>
                <b-col lg=6>
                    <b-form-group>
                        <label>App Year</label>
                        <b-form-input 
                            v-model="forms.period.fields.app_year"
                            type="text" 
                            placeholder="App Year"
                            readonly>
                        </b-form-input>
                    </b-form-group>
                </b-col>
                <b-col lg=6>
                    <b-form-group>
                        <label>Month</label>
                        <b-form-input 
                            v-model="forms.period.fields.month_name"
                            type="text" 
                            placeholder="Month"
                            readonly>
                        </b-form-input>
                    </b-form-group>
                </b-col>
            </b-row>
            <div slot="modal-footer">
                <b-button variant="primary" @click="showAdjustmentList()">
                    <i class="fa fa-check"></i>
                    OK
                </b-button>
                <b-button variant="secondary" @click="showModalPeriod=false">Close</b-button>            
            </div>
        </b-modal>
        <b-modal 
                v-model="showModalEntry"
                :noCloseOnEsc="true"
                :noCloseOnBackdrop="true"
            >
            <!-- @shown="focusElement('department_code')" -->
            <div slot="modal-title">
                Adjustment Entry - {{entryMode}}
            </div>

            <b-col lg=12>
                <b-form @keydown="resetFieldStates('adjustment')" autocomplete="off">
                    <b-form-group>
                        <label>Adjustment No.</label>
                        <b-form-input 
                            v-model="forms.adjustment.fields.adjustment_no"
                            type="text"
                            placeholder="Auto"
                            readonly>
                        </b-form-input>
                    </b-form-group>
                    <b-form-group>
                        <label for="tenant_id">* Tenant</label>
                         <select2
                            ref="tenant_id"
                            :allowClear="false"
                            :placeholder="'Select Tenant'"
                            v-model="forms.adjustment.fields.tenant_id"
                        >
                            <option v-for="tenant in options.tenants.items" :key="tenant.tenant_id" :value="tenant.tenant_id">{{ tenant.trade_name }}</option>
                        </select2>
                    </b-form-group>
                    <b-form-group>
                        <label>* Amount </label>
                        <vue-autonumeric 
                            ref="amount"
                            v-model="forms.adjustment.fields.amount"
                            :class="'form-control text-right'" 
                            :options="{minimumValue: 0, modifyValueOnWheel: false, emptyInputBehavior: 0}">
                        </vue-autonumeric>
                    </b-form-group>
                    <b-form-group>
                        <label>* Adjustment Type</label>
                        <b-form-radio-group 
                            ref="adjustment_type"
                            id="is_auto"
                            v-model="forms.adjustment.fields.adjustment_type">
                            <b-form-radio value="0">In</b-form-radio>
                            <b-form-radio value="1">Out</b-form-radio>
                        </b-form-radio-group>
                    </b-form-group>
                    <b-form-group>
                        <label>Notes</label>
                        <b-form-textarea
                            v-model="forms.adjustment.fields.notes"
                            type="text"
                            :rows="3"
                            placeholder="Notes">
                        </b-form-textarea>
                    </b-form-group>
                    <!-- <b-form-group>
                        <label>* Department Desc</label>
                        <b-form-input
                            id="department_desc"
                            v-model="forms.adjustment.fields.department_desc"
                        :state="forms.adjustment.states.department_desc"
                            type="text"
                            placeholder="Department Description">
                        </b-form-input>
                        <b-form-invalid-feedback>
                            <i class="fa fa-exclamation-triangle text-danger"></i>
                            <span v-for="itemError in forms.adjustment.errors.department_desc">
                                {{itemError}}
                            </span>
                        </b-form-invalid-feedback>
                    </b-form-group> -->
                </b-form>
            </b-col>

            <div slot="modal-footer">
                <b-button :disabled="forms.adjustment.isSaving" variant="primary" @click="onAdjustmentEntry">
                    <icon v-if="forms.adjustment.isSaving" name="sync" spin></icon>
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
                Delete Adjustment
            </div>
            <b-col lg=12>
                Are you sure you want to delete this adjustment?
            </b-col>
            <div slot="modal-footer">
                <b-button :disabled="forms.adjustment.isSaving" variant="primary" @click="onAdjustmentDelete">
                    <icon v-if="forms.adjustment.isSaving" name="sync" spin></icon>
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
    name: 'adjustment',
    data () {
        return {
            entryMode: 'Add',
            showList: false,
            showModalEntry: false,
            showModalDelete: false,
            showModalCharges: false,
            showModalPeriod: true,
            options: {
                periods: {
                    items: []
                },
                months: {
                    items: []
                },
                departments: {
                    items: []
                },
                tenants: {
                    items: []
                },
            },
            tables: {
                adjustments: {
                    fields: [
                        {
                            key: 'adjustment_id',
                            thClass: 'd-none',
                            tdClass: 'd-none'
                        },
                        {
                            key: 'adjustment_no',
                            label: 'Adjustment No.',
                        },
                        {
                            key: 'trade_name',
                            label: 'Tenant',
                        },
                        {
                            key: 'adjustment_type',
                            label: 'Adjustment Type',
                            formatter: (value) => {
                                if(value == 0){
                                    return 'In'
                                }
                                return 'Out'
                            }
                        },
                        {
                            key: 'amount',
                            label: 'Amount',
                            thClass: 'text-right',
                            tdClass: 'text-right',
                            formatter: (value) => {
                                return this.formatNumber(value)
                            }
                        },
                        {
                            key: 'notes',
                            label: 'Notes'
                        },
                        {
                            key: 'action',
                            label: 'Action',
                            thClass: 'text-center',
                            thStyle: {width: '115px'},
                            tdClass: 'text-center'
                        }
                    ],
                    items: []
                }
            },
            forms: {
                adjustment: {
                    isSaving: false,
                    fields: {
                        adjustment_id: null,
                        adjustment_no: null,
                        tenant_id: null,
                        period_id: null,
                        app_year: null,
                        month_id: null,
                        adjustment_type: null,
                        amount: 0,
                        notes: null
                    }
                },
                period: {
                    fields: {
                        period_id: null,
                        period_due_date: null,
                        app_year: null,
                        month_id: null,
                        month_name: null
                    }
                }
            },
            filters: {
                adjustments: {
                    criteria: null
                }
            },
            paginations: {
                adjustments: {
                    totalRows: 0,
                    currentPage: 1,
                    perPage: 10
                }
            },
            adjustment_id: null,
            row: []
        }
    },
    methods:{
        onAdjustmentEntry () {
            this.forms.adjustment.fields.period_id = this.forms.period.fields.period_id
            this.forms.adjustment.fields.app_year = this.forms.period.fields.app_year
            this.forms.adjustment.fields.month_id = this.forms.period.fields.month_id

            if(this.entryMode == 'Add'){
                this.createEntity('adjustment', true, 'adjustments', true)
            }
            else{
                this.updateEntity('adjustment', 'adjustment_id', true, this.row)
            }
        },
        onAdjustmentDelete(){
            this.deleteEntity('adjustment', this.adjustment_id, true, 'adjustments', 'adjustment_id')
        },
        setDelete(data){
            if(this.checkIfUsed('adjustment', data.item.adjustment_id) == true){
                this.$notify({
                    type: 'error',
                    group: 'notification',
                    title: 'Error!',
                    text: "Unable to delete, this record is being used by other transactions."
                })
                return
            }
            this.adjustment_id = data.item.adjustment_id
            this.showModalDelete = true
        },
        setUpdate(data){
            this.row = data.item
            this.fillEntityForm('adjustment', data.item.adjustment_id)
            this.showModalEntry=true
            this.entryMode='Edit'
        },

        getPeriodInfo: function (value, data){
            if(data.length > 0){
                var period = this.options.periods.items[data[0].element.index]
                this.forms.period.fields.period_due_date = moment(period.period_due_date).format('MMMM DD, YYYY')
                this.forms.period.fields.app_year = period.app_year
                this.forms.period.fields.month_name = period.month_name
                this.forms.period.fields.month_id = period.month_id
                this.forms.period.fields.due_date = moment(period.period_due_date).format('MMMM DD, YYYY')
            }
        },

        showAdjustmentList(){
            if(this.forms.period.fields.period_id != null){
                this.$http.get('/api/adjustments/' + this.forms.period.fields.period_id ,{
                    headers: {
                            Authorization: 'Bearer ' + localStorage.getItem('token')
                        }
                    })
                    .then((response) => {
                        const records = response.data
                        this.tables.adjustments.items = records.data
                        this.paginations.adjustments.totalRows = records.data.length
                    }).catch(error => {
                        if (!error.response) return
                        console.log(error)
                    })
                this.showList = true
                this.showModalPeriod = false
            }
        },
    },
    created(){
        this.fillOptionsList('periods')
        this.fillOptionsList('months')
        this.fillOptionsList('departments')
        this.fillOptionsList('tenants')
    },
    // watch: {
    //     showModalEntry: function (showModalEntry) {
    //         if(showModalEntry){
    //             let self = this
    //             Vue.nextTick(function(){
    //                 self.focusElement('tenant_id', true)
    //             })
    //         }
    //     },
    // }
}
</script>