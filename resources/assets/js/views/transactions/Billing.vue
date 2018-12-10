<template>
    <div>
        <notifications group="notification" />
        <div v-show="showEntry === false && showList === true" class="animated fadeIn">
            <b-row>
                <b-col sm="12">
                    <b-card >
                        <h5 slot="header">
                            <span class="text-primary">
                                <i class="fa fa-bars"></i> 
                                Billing List
                                <small>( {{ forms.period.fields.month_name + ' ' + forms.period.fields.app_year }} )</small>
                                <small class="font-italic">List of all billings.</small></span>
                        </h5>
                        <b-row class="mb-2">
                            <b-col sm="4">
                                    <b-button variant="primary" 
                                        @click="showEntry = true, entryMode='Add', tables.schedules.items=[], tables.utilities.items=[], tables.miscellaneous.items=[], tables.other.items=[], clearFields('billing'), counter = 0 ">
                                            <i class="fa fa-plus-circle"></i> Create New Billing
                                    </b-button>
                            </b-col>

                            <b-col  sm="4">
                                <span></span>
                            </b-col>

                            <b-col  sm="4">
                                <b-form-input 
                                            v-model="filters.billings.criteria"
                                            type="text" 
                                            placeholder="Search">
                                </b-form-input>
                            </b-col>
                        </b-row>

                        <b-row>
                            <b-col sm="12">
                                <b-table 
                                    :filter="filters.billings.criteria"
                                    :fields="tables.billings.fields"
                                    :items.sync="tables.billings.items"
                                    :current-page="paginations.billings.currentPage"
                                    :per-page="paginations.billings.perPage"
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
                                            :total-rows="paginations.billings.totalRows"
                                            :per-page="paginations.billings.perPage"
                                            v-model="paginations.billings.currentPage" />
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
                                Billing Entry - {{entryMode}}
                            </span>
                        </h5>
                        <b-row>
                            <b-col sm="9">
                                <b-card>
                                    <h6 slot="header">
                                        Details
                                    </h6>
                                    <b-row>
                                        <b-col lg="6">
                                            <b-form-group>
                                                <b-row>
                                                    <b-col lg=4>
                                                        <label class="col-form-label">Billing No. :</label>
                                                    </b-col>
                                                    <b-col lg="8">
                                                        <b-form-input
                                                            type="text"
                                                            placeholder="Auto" 
                                                            v-model="forms.billing.fields.billing_no"
                                                            readonly>
                                                        </b-form-input>
                                                    </b-col>
                                                </b-row>
                                            </b-form-group>
                                            <b-form-group>
                                                <b-row>
                                                    <b-col lg=4>
                                                        <label class="col-form-label">Tenant : </label>
                                                    </b-col>
                                                    <b-col lg="8">
                                                        <select2
                                                            @input="getTenantInfo"
                                                            :allowClear="false"
                                                            :placeholder="'Select Tenants'"
                                                            v-model="forms.billing.fields.tenant_id"
                                                        >
                                                            <option v-for="tenant in options.tenants.items" :key="tenant.tenant_id" :value="tenant.tenant_id">{{tenant.trade_name}}</option>
                                                        </select2>
                                                    </b-col>
                                                </b-row>
                                            </b-form-group>
                                            <b-form-group>
                                                <b-row>
                                                    <b-col lg=4>
                                                        <label class="col-form-label">Tenant Code : </label>
                                                    </b-col>
                                                    <b-col lg="8">
                                                        <b-form-input
                                                            type="text"
                                                            placeholder="Tenant Code" 
                                                            v-model="forms.billing.fields.tenant_code"
                                                            readonly>
                                                        </b-form-input>
                                                    </b-col>
                                                </b-row>
                                            </b-form-group>
                                            <b-form-group>
                                                <b-row>
                                                    <b-col lg=4>
                                                        <label class="col-form-label">Contract No : </label>
                                                    </b-col>
                                                    <b-col lg="8">
                                                        <select2
                                                            @input="getContractInfo"
                                                            :allowClear="false"
                                                            :placeholder="'Select Contracts'"
                                                            v-model="forms.billing.fields.contract_id"
                                                        >
                                                            <option v-for="contract in options.contracts.items" :key="contract.contract_id" :value="contract.contract_id">{{contract.contract_no}}</option>
                                                        </select2>
                                                    </b-col>
                                                </b-row>
                                            </b-form-group>
                                        </b-col>
                                        <b-col lg="6">
                                            <b-form-group>
                                                <b-row>
                                                    <b-col lg=4>
                                                        <label class="col-form-label">Commencement Date : </label>
                                                    </b-col>
                                                    <b-col lg="8">
                                                        <b-form-input
                                                            type="text"
                                                            placeholder="Commencement Date" 
                                                            v-model="this.commencement_date"
                                                            readonly>
                                                        </b-form-input>
                                                    </b-col>
                                                </b-row>
                                            </b-form-group>
                                            <b-form-group>
                                                <b-row>
                                                    <b-col lg=4>
                                                        <label class="col-form-label">Termination Date : </label>
                                                    </b-col>
                                                    <b-col lg="8">
                                                        <b-form-input
                                                            type="text"
                                                            placeholder="Termination Date" 
                                                            v-model="this.termination_date"
                                                            readonly>
                                                        </b-form-input>
                                                    </b-col>
                                                </b-row>
                                            </b-form-group>
                                            <b-form-group>
                                                <b-row>
                                                    <b-col lg=4>
                                                        <label class="col-form-label">Fixed Rent : </label>
                                                    </b-col>
                                                    <b-col lg="8">
                                                        <vue-autonumeric 
                                                            v-model="forms.billing.fields.contract_fixed_rent"
                                                            :class="'form-control text-right'" 
                                                            :options="{minimumValue: 0, modifyValueOnWheel: false, emptyInputBehavior: 0}"
                                                            readonly>
                                                        </vue-autonumeric>
                                                    </b-col>
                                                </b-row>
                                            </b-form-group>
                                            <b-form-group>
                                                <b-row>
                                                    <b-col lg=4>
                                                        <label class="col-form-label">Due Date : </label>
                                                    </b-col>
                                                    <b-col lg="8">
                                                        <b-form-input
                                                            type="text"
                                                            placeholder="Due Date" 
                                                            v-model="forms.period.fields.period_due_date"
                                                            readonly>
                                                        </b-form-input>
                                                    </b-col>
                                                </b-row>
                                            </b-form-group>
                                        </b-col>
                                    </b-row>
                                </b-card>
                            <!-- </b-col>
                            <b-col sm="7"> -->
                                <b-card>
                                    <b-row slot="header">
                                        <b-col sm="4">
                                            <h6>
                                                Schedule
                                            </h6>
                                        </b-col>
                                        <b-col sm="4">
                                            <span></span>
                                        </b-col>
                                        <b-col sm="4">
                                            <b-button class="float-right" variant="primary" @click="addSchedule(), counter = 0">
                                                <i class="fa fa-plus-circle"></i> Add Schedule
                                            </b-button>    
                                        </b-col>
                                    </b-row>
                                    

                                    <b-row>
                                        <b-col lg="12">
                                            <b-table 
                                                small bordered
                                                :fields="tables.schedules.fields"
                                                :items.sync="tables.schedules.items"
                                                show-empty>
                                                <template slot="month_name" slot-scope="data">
                                                    <select2
                                                        :allowClear="false"
                                                        :placeholder="'Select Month'"
                                                        v-model="data.item.month_id"
                                                    >
                                                        <option v-for="month in options.months.items" :key="month.month_id" :value="month.month_id">{{month.month_name}}</option>
                                                    </select2>
                                                </template>
                                                <template slot="escalation_percent" slot-scope="data">
                                                    <vue-autonumeric 
                                                        :class="'form-control text-right'"
                                                        v-model="data.item.escalation_percent" 
                                                        :options="{minimumValue: 0, maximumValue: 100,modifyValueOnWheel: false, emptyInputBehavior: 0}">
                                                    </vue-autonumeric>
                                                </template>
                                                <template slot="is_vatted" slot-scope="data">
                                                    <b-form-checkbox
                                                        v-model="data.item.is_vatted"
                                                        value=1
                                                        unchecked-value=0>
                                                    </b-form-checkbox>
                                                </template>
                                                <template slot="contract_schedule_notes" slot-scope="data">
                                                    <b-form-input 
                                                        id="contract_schedule_notes"
                                                        placeholder="Notes"
                                                        v-model="data.item.contract_schedule_notes">
                                                    </b-form-input>
                                                </template>
                                                <template slot="action" slot-scope="data">
                                                    <b-btn :size="'sm'" variant="danger" @click="removeSchedule()">
                                                        <i class="fa fa-times-circle"></i>
                                                    </b-btn>
                                                </template>
                                            </b-table>
                                        </b-col>
                                    </b-row>
                                </b-card>
                                <b-card>
                                    <b-row slot="header">
                                        <b-col sm="4">
                                            <h6>
                                                Charges
                                            </h6>
                                        </b-col>
                                    </b-row>
                                    <b-row class="mb-2">
                                        <b-col sm="4">
                                            <h5>Utility</h5>
                                        </b-col>
                                        <b-col  sm="4">
                                            <span></span>
                                        </b-col>
                                        <b-col  sm="4">
                                            <b-button class="float-right" variant="primary" @click="showModalCharges = true, charge_type='utilities'">
                                                <i class="fa fa-plus-circle"></i> Add Charges
                                            </b-button>
                                        </b-col>
                                    </b-row>
                                    <b-table 
                                        small bordered
                                        :fields="tables.utilities.fields"
                                        :items.sync="tables.utilities.items"
                                        show-empty>
                                        <template slot="contract_rate" slot-scope="data">
                                            <vue-autonumeric 
                                                :class="'form-control text-right'"
                                                v-model="data.item.contract_rate" 
                                                :options="{minimumValue: 0,modifyValueOnWheel: false, emptyInputBehavior: 0}">
                                            </vue-autonumeric>
                                        </template>
                                        <template slot="contract_default_reading" slot-scope="data">
                                            <vue-autonumeric 
                                                :class="'form-control text-right'"
                                                v-model="data.item.contract_default_reading" 
                                                :options="{minimumValue: 0,modifyValueOnWheel: false, emptyInputBehavior: 0}">
                                            </vue-autonumeric>
                                        </template>
                                        <template slot="contract_is_vatted" slot-scope="data">
                                            <b-form-checkbox
                                                v-model="data.item.contract_is_vatted"
                                                value=1
                                                unchecked-value=0>
                                            </b-form-checkbox>
                                        </template>
                                        <template slot="contract_notes" slot-scope="data">
                                            <b-form-input 
                                                placeholder="Notes"
                                                v-model="data.item.contract_notes">
                                            </b-form-input>
                                        </template>
                                        <template slot="action" slot-scope="data">
                                            <b-btn :size="'sm'" variant="danger" @click="removeCharge('utilities', data.index)">
                                                <i class="fa fa-times-circle"></i>
                                            </b-btn>
                                        </template>
                                    </b-table>
                                    <b-row class="mb-2">
                                        <b-col sm="4">
                                            <h5>Miscellaneous</h5>
                                        </b-col>
                                    <b-col  sm="4">
                                            <span></span>
                                        </b-col>
                                        <b-col  sm="4">
                                            <b-button class="float-right" variant="primary" @click="showModalCharges = true, charge_type='miscellaneous'">
                                                <i class="fa fa-plus-circle"></i> Add Charges
                                            </b-button>
                                        </b-col>
                                    </b-row>
                                    <b-table 
                                        small bordered
                                        :fields="tables.miscellaneous.fields"
                                        :items.sync="tables.miscellaneous.items"
                                        show-empty>
                                        <template slot="contract_rate" slot-scope="data">
                                            <vue-autonumeric 
                                                :class="'form-control text-right'"
                                                v-model="data.item.contract_rate" 
                                                :options="{minimumValue: 0,modifyValueOnWheel: false, emptyInputBehavior: 0}">
                                            </vue-autonumeric>
                                        </template>
                                        <template slot="contract_default_reading" slot-scope="data">
                                            <vue-autonumeric 
                                                :class="'form-control text-right'"
                                                v-model="data.item.contract_default_reading" 
                                                :options="{minimumValue: 0,modifyValueOnWheel: false, emptyInputBehavior: 0}">
                                            </vue-autonumeric>
                                        </template>
                                        <template slot="contract_is_vatted" slot-scope="data">
                                            <b-form-checkbox
                                                v-model="data.item.contract_is_vatted"
                                                value=1
                                                unchecked-value=0>
                                            </b-form-checkbox>
                                        </template>
                                        <template slot="contract_notes" slot-scope="data">
                                            <b-form-input 
                                                placeholder="Notes"
                                                v-model="data.item.contract_notes">
                                            </b-form-input>
                                        </template>
                                        <template slot="action" slot-scope="data">
                                            <b-btn :size="'sm'" variant="danger" @click="removeCharge('miscellaneous', data.index)">
                                                <i class="fa fa-times-circle"></i>
                                            </b-btn>
                                        </template>
                                    </b-table>
                                    <b-row class="mb-2">
                                        <b-col sm="4">
                                            <h5>Other</h5>
                                        </b-col>
                                        <b-col sm="4">
                                            <span></span>
                                        </b-col>
                                        <b-col  sm="4">
                                            <b-button class="float-right" variant="primary" @click="showModalCharges = true, charge_type='other'">
                                                <i class="fa fa-plus-circle"></i> Add Charges
                                            </b-button>
                                        </b-col>
                                    </b-row>
                                    <b-table 
                                        small bordered
                                        :fields="tables.other.fields"
                                        :items.sync="tables.other.items"
                                        show-empty>
                                        <template slot="contract_rate" slot-scope="data">
                                            <vue-autonumeric 
                                                :class="'form-control text-right'"
                                                v-model="data.item.contract_rate" 
                                                :options="{minimumValue: 0,modifyValueOnWheel: false, emptyInputBehavior: 0}">
                                            </vue-autonumeric>
                                        </template>
                                        <template slot="contract_default_reading" slot-scope="data">
                                            <vue-autonumeric 
                                                :class="'form-control text-right'"
                                                v-model="data.item.contract_default_reading" 
                                                :options="{minimumValue: 0,modifyValueOnWheel: false, emptyInputBehavior: 0}">
                                            </vue-autonumeric>
                                        </template>
                                        <template slot="contract_is_vatted" slot-scope="data">
                                            <b-form-checkbox
                                                v-model="data.item.contract_is_vatted"
                                                value=1
                                                unchecked-value=0>
                                            </b-form-checkbox>
                                        </template>
                                        <template slot="contract_notes" slot-scope="data">
                                            <b-form-input 
                                                placeholder="Notes"
                                                v-model="data.item.contract_notes">
                                            </b-form-input>
                                        </template>
                                        <template slot="action" slot-scope="data">
                                            <b-btn :size="'sm'" variant="danger" @click="removeCharge('other', data.index)">
                                                <i class="fa fa-times-circle"></i>
                                            </b-btn>
                                        </template>
                                    </b-table>
                                </b-card>
                            </b-col>
                            <b-col sm="3">
                                <b-card>
                                    <h6 slot="header">
                                        Summary
                                    </h6>
                                    <b-row>
                                        <label class="font-weight-bold col-sm-6">Prev. Balance :</label>
                                        <label class="font-weight-bold col-sm-6 text-right"> {{formatNumber(previous_balance)}} </label>
                                    </b-row>
                                    <b-row>
                                        <label class="font-weight-bold col-sm-6">Sub Total :</label>
                                        <label class="font-weight-bold col-sm-6 text-right"> {{formatNumber(getSubTotal)}} </label>
                                    </b-row>
                                    <b-row>
                                        <label class="font-weight-bold col-sm-6 text-right">Vatable :</label>
                                        <label class="font-weight-bold col-sm-6 text-right"> {{formatNumber(getVatables)}} </label>
                                    </b-row>
                                    <b-row>
                                        <label class="font-weight-bold col-sm-6 text-right col-form-label"> X </label>
                                        <b-col sm="6">
                                            <vue-autonumeric 
                                                :class="'text-right form-control'" 
                                                v-model="forms.billing.fields.vat_percent" 
                                                :options="{minimumValue: 0, maximumValue: 100, modifyValueOnWheel: false, emptyInputBehavior: 0}">
                                            </vue-autonumeric>
                                        </b-col>
                                    </b-row>
                                    <hr>
                                    <b-row>
                                        <label class="font-weight-bold col-sm-6">Vat Total :</label>
                                        <label class="font-weight-bold col-sm-6 text-right"> {{formatNumber(getVatTotal)}} </label>
                                    </b-row>
                                    <b-row>
                                        <label class="font-weight-bold col-sm-6 text-right">Fixed Rent :</label>
                                        <label class="font-weight-bold col-sm-6 text-right"> {{formatNumber(forms.billing.fields.total_fixed_rent)}} </label>
                                    </b-row>
                                    <b-row>
                                        <label class="font-weight-bold col-sm-6 text-right col-form-label"> X </label>
                                        <b-col sm="6">
                                            <vue-autonumeric 
                                                :class="'text-right form-control'" 
                                                v-model="whtax_percent" 
                                                :options="{minimumValue: 0, maximumValue: 100, modifyValueOnWheel: false, emptyInputBehavior: 0}">
                                            </vue-autonumeric>
                                        </b-col>
                                    </b-row>
                                    <hr>
                                    <b-row>
                                        <label class="font-weight-bold col-sm-6">WH Tax :</label>
                                        <label class="font-weight-bold col-sm-6 text-right"> ({{formatNumber(getWithHoldingTax)}}) </label>
                                    </b-row>
                                    <hr>
                                    <b-row>
                                        <label class="font-weight-bold col-sm-6">Total Due :</label>
                                        <label class="font-weight-bold col-sm-6 text-right"> {{formatNumber(getTotalDue)}} </label>
                                    </b-row>
                                    <b-row>
                                        <label class="font-weight-bold col-sm-6">End Balance :</label>
                                        <label class="font-weight-bold col-sm-6 text-right"> {{formatNumber(getEndingBalance)}} </label>
                                    </b-row>
                                </b-card>
                            </b-col>                     
                        </b-row>
                        <hr>
                        <b-row class="pull-right mt-2">
                            <b-col sm="12">
                                <b-button 
                                    :disabled="forms.billing.isSaving" 
                                    variant="primary" 
                                    @click="onBillingEntry">
                                    <icon v-if="forms.billing.isSaving" name="sync" spin></icon>
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
            v-model="showModalPeriod"
            :noCloseOnEsc="true"
            :noCloseOnBackdrop="true"
        >
            <div slot="modal-title">
                Billing Period
            </div>
            
            <b-row>
                <b-col lg=12>
                    <b-form-group>
                        <label>Billing Period</label>
                        <select2
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
            <b-row>
                <b-col lg=12>
                    <b-form-group>
                        <label>Department</label>
                        <select2
                            :allowClear="false"
                            :placeholder="'Select Department'"
                            v-model="forms.period.fields.department_id"
                        >
                            <option v-for="department in options.departments.items" :key="department.department_id" :value="department.department_id">{{department.department_desc}}</option>
                        </select2>
                    </b-form-group>
                </b-col>
            </b-row>
            <div slot="modal-footer">
                <b-button variant="primary" @click="showBillingList()">
                    <i class="fa fa-check"></i>
                    OK
                </b-button>
                <b-button variant="secondary" @click="showModalPeriod=false">Close</b-button>            
            </div>
        </b-modal>
        <b-modal 
            v-model="showModalCharges"
            :noCloseOnEsc="true"
            :noCloseOnBackdrop="true"
        >
            <div slot="modal-title">
                Charges
            </div>
            <b-col lg=12>
                <b-table 
                    small bordered
                    :fields="tables.charges.fields"
                    :items.sync="tables.charges.items"
                    show-empty>
                    <template slot="is_selected" slot-scope="data">
                        <input type="checkbox" v-model="data.item.is_selected">
                    </template>
                </b-table>
            </b-col>
            <div slot="modal-footer">
                <b-button variant="primary" @click="addCharges(charge_type)">
                    <i class="fa fa-check"></i>
                    Save
                </b-button>
                <b-button variant="secondary" @click="showModalCharges=false">Close</b-button>            
            </div>
        </b-modal>
        <b-modal 
            v-model="showModalDelete"
            :noCloseOnEsc="true"
            :noCloseOnBackdrop="true"
        >
            <div slot="modal-title">
                Delete Billing
            </div>
            <b-col lg=12>
                Are you sure you want to delete this billing?
            </b-col>
            <div slot="modal-footer">
                <b-button :disabled="forms.billing.isSaving" variant="primary" @click="onBillingDelete">
                    <icon v-if="forms.billing.isSaving" name="sync" spin></icon>
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
    name: 'billing',
    data () {
        return {
            entryMode: 'Add',
            showList: false,
            showEntry: false,
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
                contracts: {
                    items: []
                }
            },
            tables: {
                billings: {
                    fields: [
                        {
                            key: 'billing_id',
                            thClass: 'd-none',
                            tdClass: 'd-none'
                        },
                        {
                            key: 'billing_no',
                            label: 'Billing No.',
                        },
                        {
                            key: 'contract_no',
                            label: 'Contract No.',
                        },
                        {
                            key: 'app_year',
                            label: 'App Year/Month',
                            formatter: (value, key, item) => {
                                return item.app_year + '/' + item.month_name
                            }
                        },
                        {
                            key: 'trade_name',
                            label: 'Tenant',
                        },
                        {
                            key: 'tenant_code',
                            label: 'Tenant Code'
                        },
                        {
                            key: 'total_amount_due',
                            label: 'Total Amount Due',
                            thClass: 'text-right',
                            tdClass: 'text-right',
                            formatter: (value) => {
                                return this.formatNumber(value)
                            }
                        },
                        {
                            key: 'action',
                            label: 'Action',
                            thClass: 'text-center',
                            thStyle: {width: '75px'},
                            tdClass: 'text-center'
                        }
                    ],
                    items: []
                },
                charges: {
                    fields: [
                        {
                            key: 'charge_id',
                            thClass: 'd-none',
                            tdClass: 'd-none'
                        },
                        {
                            key: 'is_selected',
                            label: '',
                            tdClass: 'text-center'
                        },
                        {
                            key:'charge_code',
                            label: 'Charge Code'
                        },
                        {
                            key:'charge_desc',
                            label: 'Description'
                        },
                    ],
                    items:[]
                },
                schedules: {
                    fields: [
                        {
                            key: 'count',
                            label: '',
                            tdClass: 'align-middle',
                            thStyle: {width: '3%'},
                        },
                        {
                            key: 'month_name',
                            label: 'Month',
                            thStyle: {width: '20%'},
                            tdClass: 'align-middle'
                        },
                        {
                            key: 'app_year',
                            label: 'Year',
                            tdClass: 'align-middle',
                            thStyle: {width: '5%'},
                        },
                        {
                            key: 'amount_due',
                            label: 'Amount Due',
                            thClass: 'text-right',
                            tdClass: 'text-right align-middle',
                            thStyle: {width: '15%'},
                            formatter: (value) => {
                                return this.formatNumber(value)
                                
                            }
                        },
                        {
                            key: 'is_vatted',
                            label: 'Is Vatted?',
                            thClass: 'text-center',
                            tdClass: 'text-center align-middle',
                            thStyle: {width: '10%'},
                        },
                        {
                            key: 'contract_schedule_notes',
                            label: 'Notes'
                        },
                        {
                            key: 'action',
                            label: 'Action',
                            thClass: 'text-center',
                            tdClass: 'text-center',
                            thStyle: {width: '50px'},
                        }
                    ],
                    items: []
                },
                utilities: {
                    fields: [
                        {
                            key: 'charge_id',
                            label: '',
                            thClass: 'd-none',
                            tdClass: 'd-none'
                        },
                        {
                            key: 'charge_desc',
                            label: 'Description',
                            tdClass: 'align-middle',
                            thStyle: {width: '18%'}
                        },
                        {
                            key: 'contract_rate',
                            label: 'Rate',
                            thClass: 'text-right',
                            tdClass: 'align-middle text-right',
                            thStyle: {width: '15%'}
                        },
                        {
                            key: 'contract_default_reading',
                            label: 'Default Reading',
                            thClass: 'text-right',
                            tdClass: 'text-right align-middle',
                            thStyle: {width: '17%'}
                        },
                        {
                            key: 'contract_line_total',
                            label: 'Line Total',
                            thClass: 'text-right',
                            tdClass: 'text-right align-middle',
                            thStyle: {width: '17%'},
                            formatter: (value, key, item) => {
                                item.contract_line_total = item.contract_rate * item.contract_default_reading
                                return this.formatNumber(item.contract_line_total)
                            }

                        },
                        {
                            key: 'contract_is_vatted',
                            label: 'Is Vatted?',
                            thClass: 'text-center',
                            tdClass: 'text-center align-middle',
                            thStyle: {width: '10%'}
                        },
                        {
                            key: 'contract_notes',
                            label: 'Notes'
                        },
                        {
                            key: 'action',
                            label: 'Action',
                            thClass: 'text-center',
                            thStyle: {width: '50px'},
                            tdClass: 'text-center'
                        }
                    ],
                    items: []
                },
                miscellaneous: {
                    fields: [
                        {
                            key: 'charge_id',
                            label: '',
                            thClass: 'd-none',
                            tdClass: 'd-none'
                        },
                        {
                            key: 'charge_desc',
                            label: 'Description',
                            tdClass: 'align-middle',
                            thStyle: {width: '18%'}
                        },
                        {
                            key: 'contract_rate',
                            label: 'Rate',
                            thClass: 'text-right',
                            tdClass: 'align-middle text-right',
                            thStyle: {width: '15%'}
                        },
                        {
                            key: 'contract_default_reading',
                            label: 'Default Reading',
                            thClass: 'text-right',
                            tdClass: 'text-right align-middle',
                            thStyle: {width: '17%'}
                        },
                        {
                            key: 'contract_line_total',
                            label: 'Line Total',
                            thClass: 'text-right',
                            tdClass: 'text-right align-middle',
                            thStyle: {width: '17%'},
                            formatter: (value, key, item) => {
                                item.contract_line_total = item.contract_rate * item.contract_default_reading
                                return this.formatNumber(item.contract_line_total)
                            }

                        },
                        {
                            key: 'contract_is_vatted',
                            label: 'Is Vatted?',
                            thClass: 'text-center',
                            tdClass: 'text-center align-middle',
                            thStyle: {width: '10%'}
                        },
                        {
                            key: 'contract_notes',
                            label: 'Notes'
                        },
                        {
                            key: 'action',
                            label: 'Action',
                            thClass: 'text-center',
                            thStyle: {width: '50px'},
                            tdClass: 'text-center'
                        }
                    ],
                    items: []
                },
                other: {
                    fields: [
                        {
                            key: 'charge_id',
                            label: '',
                            thClass: 'd-none',
                            tdClass: 'd-none'
                        },
                        {
                            key: 'charge_desc',
                            label: 'Description',
                            tdClass: 'align-middle',
                            thStyle: {width: '18%'}
                        },
                        {
                            key: 'contract_rate',
                            label: 'Rate',
                            thClass: 'text-right',
                            tdClass: 'align-middle text-right',
                            thStyle: {width: '15%'}
                        },
                        {
                            key: 'contract_default_reading',
                            label: 'Default Reading',
                            thClass: 'text-right',
                            tdClass: 'text-right align-middle',
                            thStyle: {width: '17%'}
                        },
                        {
                            key: 'contract_line_total',
                            label: 'Line Total',
                            thClass: 'text-right',
                            tdClass: 'text-right align-middle',
                            thStyle: {width: '17%'},
                            formatter: (value, key, item) => {
                                item.contract_line_total = item.contract_rate * item.contract_default_reading
                                return this.formatNumber(item.contract_line_total)
                            }

                        },
                        {
                            key: 'contract_is_vatted',
                            label: 'Is Vatted?',
                            thClass: 'text-center',
                            tdClass: 'text-center align-middle',
                            thStyle: {width: '10%'}
                        },
                        {
                            key: 'contract_notes',
                            label: 'Notes'
                        },
                        {
                            key: 'action',
                            label: 'Action',
                            thClass: 'text-center',
                            thStyle: {width: '50px'},
                            tdClass: 'text-center'
                        }
                    ],
                    items: []
                }
            },
            forms: {
                billing: {
                    isSaving: false,
                    fields: {
                        billing_id: null,
                        billing_no: null,
                        tenant_id: null,
                        tenant_code: null,
                        period_id: null,
                        period_due_date: null,
                        app_year: null,
                        month_id: null,
                        contract_id: null,
                        contract_no: null,
                        commencement_date: null,
                        termination_date: null,
                        contract_fixed_rent: null,
                        due_date: null,
                        total_fixed_rent: 0,
                        total_util_charges: 0,
                        total_misc_charges: 0,
                        total_othr_charges: 0,
                        sub_total: 0,
                        vattable_amount: 0,
                        vat_percent: 0,
                        total_vat: 0,
                        total_amount_due: 0,
                        wtax_amount: 0,
                        schedules: [],
                        utilities: [],
                        miscellaneous: [],
                        other: []
                    }
                },
                period: {
                    fields: {
                        period_id: null,
                        period_due_date: null,
                        app_year: null,
                        month_id: null,
                        month_name: null,
                        department_id: null
                    }
                }
            },
            filters: {
                billings: {
                    criteria: null
                }
            },
            paginations: {
                billings: {
                    totalRows: 0,
                    currentPage: 1,
                    perPage: 10
                }
            },
            counter: 0,
            charge_type: null,
            previous_balance: 0,
            whtax_percent: 0,
            billing_id: null
        }
    },
    methods:{
        onBillingEntry () {
            this.forms.billing.fields.schedules = this.tables.schedules.items
            this.forms.billing.fields.utilities = this.tables.utilities.items
            this.forms.billing.fields.miscellaneous = this.tables.miscellaneous.items
            this.forms.billing.fields.other = this.tables.other.items
            this.forms.billing.fields.period_id = this.forms.period.fields.period_id
            this.forms.billing.fields.period_due_date = this.forms.period.fields.period_due_date
            this.forms.billing.fields.app_year = this.forms.period.fields.app_year
            this.forms.billing.fields.month_id = this.forms.period.fields.month_id

            if(this.entryMode == 'Add'){
                this.createEntity('billing', false, 'billings')
            }
            else{
                this.updateEntity('billing', 'billing_id', false, 'billings')
            }
        },
        onBillingDelete(){
            this.deleteEntity('billing', this.billing_id, true, 'billings')
        },
        async setDelete(data){
            if(await this.checkIfUsed('billing', data.item.billing_id) == true){
                this.$notify({
                    type: 'error',
                    group: 'notification',
                    title: 'Error!',
                    text: "Unable to delete, this record is being used by other transactions."
                })
                return
            }
            this.billing_id = data.item.billing_id
            this.showModalDelete = true
        },
        setUpdate(data){
            this.filterOptionsList('contracts', data.item.tenant_id)
            
            this.$http.get('/api/billings/sc/'+ data.item.billing_id,{
              headers: {
                      Authorization: 'Bearer ' + localStorage.getItem('token')
                  }
            })
            .then((response) => {
                const res = response.data
                this.tables.schedules.items = res.schedules
                this.tables.utilities.items = res.util_charges
                this.tables.miscellaneous.items = res.misc_charges
                this.tables.other.items = res.othr_charges
                this.fillEntityForm('billing', data.item.billing_id)
                this.showEntry=true
                this.entryMode='Edit'
                this.counter = this.tables.schedules.items.length
            })
            .catch(error => {
              if (!error.response) return
              console.log(error)
            })
        },
        addSchedule(){
            try {
                var fixed_rent = this.forms.billing.fields.contract_fixed_rent
                
                var month;
                var escalation_percent = 0;
                var amount_due = fixed_rent;

                this.counter++;

                this.tables.schedules.items.push({
                    count: this.counter, 
                    month_id: this.forms.period.fields.month_id, 
                    app_year: this.forms.period.fields.app_year, 
                    // fixed_rent: fixed_rent, 
                    // escalation_percent: escalation_percent, 
                    amount_due: amount_due, 
                    is_vatted: 0, 
                    contract_schedule_notes: ''
                });
            } 
            catch(e) {
                console.log(e);
            }
        },
        removeSchedule(){
            this.tables.schedules.items.splice(this.tables.schedules.items.length - 1, 1)
            this.counter--
        },
        addCharges(charge_type){
            try {
                this.tables.charges.items.forEach(charge => {
                    if(charge.is_selected){
                        this.tables[charge_type].items.push({
                            charge_id: charge.charge_id,
                            charge_desc: charge.charge_desc,
                            contract_rate: 0,
                            contract_default_reading: 0,
                            contract_is_vatted: 0,
                            contract_notes:''
                        })
                        charge.is_selected = false
                    }
                });
                this.showModalCharges = false
            }
            catch(e){
                console.log(e)
            }
        },
        removeCharge(charge_type, index){
            this.tables[charge_type].items.splice(index, 1)
        },
        getPrevBalance(month_id, app_year, tenant_id){
                this.$http.get('api/billing/'+month_id+'/'+app_year+'/'+tenant_id,{
                    headers: {
                        Authorization: 'Bearer ' + localStorage.getItem('token')
                    }
                })
                .then((response) => {
                    const res = response.data
                    this.previous_balance = res[0]['prevBalance'];
                })
                .catch(error => {
                        if (!error.response) 
                        return console.log(error)
                    })
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
        getTenantInfo: function (value, data) {
            if(data.length > 0){
                var tenant = this.options.tenants.items[data[0].element.index]
                this.forms.billing.fields.tenant_code = tenant.tenant_code
                this.filterOptionsList('contracts', tenant.tenant_id)
                this.getPrevBalance(this.forms.period.fields.month_id, this.forms.period.fields.app_year, tenant.tenant_id)
            }
        },
        getContractInfo: function (value, data) {
            if(data.length > 0){
                if(data.length > 0){
                    var contract = this.options.contracts.items[data[0].element.index]
                    this.forms.billing.fields.commencement_date = moment(contract.commencement_date).format('MMMM DD, YYYY')
                    this.forms.billing.fields.termination_date = moment(contract.termination_date).format('MMMM DD, YYYY')
                    this.forms.billing.fields.contract_fixed_rent = contract.contract_fixed_rent

                    this.$http.get('/api/contracts/' + value + '/' + this.forms.period.fields.app_year + '/' + this.forms.period.fields.month_id,{
                        headers: {
                                Authorization: 'Bearer ' + localStorage.getItem('token')
                            }
                    })
                    .then((response) => {
                        const res = response.data
                        this.tables.schedules.items = res.schedules
                        this.tables.utilities.items = res.util_charges
                        this.tables.miscellaneous.items = res.misc_charges
                        this.tables.other.items = res.othr_charges
                        this.counter = this.tables.schedules.items.length
                    })
                    .catch(error => {
                        if (!error.response) 
                        return console.log(error)
                    })
                }
            }
        },
        showBillingList(){
            if(this.forms.period.fields.period_id != null && this.forms.period.fields.department_id != null){
                this.filterTableList('billings', this.forms.period.fields.period_id)
                this.showList = true
                this.showModalPeriod = false
            }
        }
    },
    computed :{
        commencement_date: function(){
            this.forms.billing.fields.commencement_date = moment(this.forms.billing.fields.commencement_date).format("MMMM DD, YYYY")
            return this.forms.billing.fields.commencement_date
        },
        termination_date: function(){
            this.forms.billing.fields.termination_date = moment(this.forms.billing.fields.termination_date).format("MMMM DD, YYYY")
            return this.forms.billing.fields.termination_date
        },
        getSubTotal: function(){
            var schedTotal = 0
            var utilTotal = 0
            var miscTotal = 0
            var othrTotal = 0

            this.tables.schedules.items.forEach(schedule => {
                if(schedule != null){
                    schedTotal += Number(schedule.amount_due)
                }        
            })

            this.tables.utilities.items.forEach(util => {
                if(util != null){
                    utilTotal += Number(util.contract_rate  * util.contract_default_reading)
                }
            })

            this.tables.miscellaneous.items.forEach(misc => {
                if(misc != null){
                    miscTotal += Number(misc.contract_rate * misc.contract_default_reading)
                }
            })

            this.tables.other.items.forEach(othr => {
                if(othr != null){
                    othrTotal += Number(othr.contract_rate * othr.contract_default_reading)
                }   
            })

            this.forms.billing.fields.total_fixed_rent = schedTotal
            this.forms.billing.fields.total_util_charges = utilTotal
            this.forms.billing.fields.total_misc_charges = miscTotal
            this.forms.billing.fields.total_othr_charges = othrTotal

            this.forms.billing.fields.sub_total = Number(this.forms.billing.fields.total_fixed_rent) + Number(this.forms.billing.fields.total_util_charges) + Number(this.forms.billing.fields.total_misc_charges) + Number(this.forms.billing.fields.total_othr_charges)
            return this.forms.billing.fields.sub_total
        },
        getVatables : function(){
            var schedVat = 0
            var utilVat = 0
            var miscVat = 0
            var othrVat = 0

            this.tables.schedules.items.forEach(schedule => {
                if(schedule.is_vatted == 1){
                    schedVat += schedule.amount_due
                }
            })

            this.tables.utilities.items.forEach(util => {
                if(util.contract_is_vatted == 1){
                    utilVat += util.contract_rate  * util.contract_default_reading
                }
            })

            this.tables.miscellaneous.items.forEach(misc => {
                if(misc.contract_is_vatted == 1){
                    miscVat += misc.contract_rate * misc.contract_default_reading
                }
            })

            this.tables.other.items.forEach(othr => {
                if(othr.contract_is_vatted == 1){
                    othrVat += othr.contract_rate * othr.contract_default_reading
                }
            })
            this.forms.billing.fields.vattable_amount = Number(schedVat) + Number(utilVat) + Number(miscVat) + Number(othrVat)
            return this.forms.billing.fields.vattable_amount
        },

        getVatTotal: function(){
            var vatTotal = this.forms.billing.fields.vattable_amount * (this.forms.billing.fields.vat_percent / 100)
            return vatTotal
        },

        getWithHoldingTax: function(){
            this.forms.billing.fields.wtax_amount = this.forms.billing.fields.total_fixed_rent * (this.whtax_percent / 100)
            return this.forms.billing.fields.wtax_amount
        },

        getTotalDue: function(){
            this.forms.billing.fields.total_amount_due = this.forms.billing.fields.sub_total + this.getVatTotal - this.forms.billing.fields.wtax_amount
            return this.forms.billing.fields.total_amount_due
        },

        getEndingBalance: function(){
            var endingBalance = this.forms.billing.fields.total_amount_due + Number(this.previous_balance)
            return endingBalance
        }
    },
    created(){
        this.fillTableList('charges')
        this.fillOptionsList('periods')
        this.fillOptionsList('months')
        this.fillOptionsList('departments')
        this.fillOptionsList('tenants')
    }
}
</script>