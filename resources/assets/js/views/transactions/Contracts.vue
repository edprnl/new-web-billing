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
                                    <b-button variant="primary" 
                                        @click="showEntry = true, entryMode='Add', tables.schedules.items=[], tables.utilities.items=[], tables.miscellaneous.items=[], tables.other.items=[], tabIndex=0, clearFields('contract'), forms.contract.fields.contract_terms = 1, computeDates(true),counter = 0 ">
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
                                    responsive
                                    :filter="filters.contracts.criteria"
                                    :fields="tables.contracts.fields"
                                    :items.sync="tables.contracts.items"
                                    :current-page="paginations.contracts.currentPage"
                                    :per-page="paginations.contracts.perPage"
                                    striped hover small bordered show-empty
                                >
                                    <template slot="row_data" slot-scope="row">
                                        <b-btn :size="'sm'" variant="success" @click.stop="row.toggleDetails()">
                                            <i :class="row.detailsShowing ? 'fa fa-minus-circle' : 'fa fa-plus-circle'"></i>
                                        </b-btn>
                                    </template>
                                    <template slot="row-details" slot-scope="row">
                                        <b-row class="font-weight-bold ml-2 mr-2">
                                            <b-col lg="4">
                                                <p>Contract No. : {{row.item.contract_no}}</p>
                                                <p>Tenant Code : {{row.item.tenant_code}}</p>
                                                <p>Tenant : {{row.item.trade_name}}</p>
                                                <p>Signatory : {{row.item.contract_signatory}}</p>
                                                <p>Billing Address : {{row.item.contract_billing_address}}</p>
                                                <p>Department : {{row.item.department_desc}}</p>
                                                <p>Nature of Business : {{row.item.nature_of_business_desc}}</p>
                                                <p>Approved Merchandise : {{row.item.contract_approved_merch}}</p>
                                            </b-col>
                                            <b-col lg="4">
                                                <p>Location : {{row.item.location_desc}}</p>
                                                <p>Contract Types : {{row.item.contract_type_desc}}</p>
                                                <p>Category : {{row.item.category_desc}}</p>
                                                <p>Terms : {{row.item.contract_terms}}</p>
                                                <p>Commencement Date : {{moment(row.item.commencement_date, 'MMMM DD, YYYY')}}</p>
                                                <p>Termination Date : {{moment(row.item.termination_date, 'MMMM DD, YYYY')}}</p>
                                                <p>Start Billing Date : {{moment(row.item.start_billing_date, 'MMMM DD, YYYY')}}</p>
                                                <p>App Floor Area : {{row.item.contract_floor_area}}</p>
                                            </b-col>
                                            <b-col lg="4">
                                                <p>Basic Rental : {{formatNumber(row.item.contract_fixed_rent)}}</p>
                                                <p>Discounted Rental : {{formatNumber(row.item.contract_discounted_rent)}}</p>
                                                <p>Advance Rental : {{formatNumber(row.item.contract_advance_rent)}}</p>
                                                <p>Security Deposit : {{formatNumber(row.item.security_deposit)}}</p>
                                                <p>Electric Meter Deposit : {{formatNumber(row.item.power_meter_deposit)}}</p>
                                                <p>Water Meter Deposit : {{formatNumber(row.item.water_meter_deposit)}}</p>
                                                <p>Construction Deposit : {{formatNumber(row.item.construction_deposit)}}</p>
                                                <p>Escalation % : {{formatNumber(row.item.contract_escalation_percent)}}</p>
                                                <p>Escalation Notes : {{row.item.escalation_notes}}</p>
                                                <p>Remarks : {{row.item.contract_remarks}}</p>
                                            </b-col>
                                        </b-row>
                                    </template>
                                    <template slot="action" slot-scope="data">
                                        <b-btn :size="'sm'" variant="primary" @click="setUpdate(data), tabIndex=0">
                                            <i class="fa fa-edit"></i>
                                        </b-btn>

                                        <b-btn :size="'sm'" variant="danger" @click="setDelete(data)">
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
                        <b-form @keydown="resetFieldStates('contract')" autocomplete="off">
                            <b-row>
                                <b-col sm="12">
                                    <b-tabs v-model="tabIndex">
                                        <b-tab title="Contract Info" >
                                            <b-row class="mb-2">
                                                <b-col sm="4">
                                                    <h5>Contract Info</h5>
                                                </b-col>
                                            </b-row>
                                            <b-row>
                                                <b-col lg="4">
                                                    <b-form-group>
                                                        <b-row>
                                                            <b-col lg="6">
                                                                <label>Contract No.</label>
                                                                <b-form-input
                                                                    v-model="forms.contract.fields.contract_no"
                                                                    type="text"
                                                                    readonly
                                                                    placeholder="Auto">
                                                                </b-form-input>
                                                            </b-col>
                                                            <b-col lg="6">
                                                                <label>Tenant Code</label>
                                                                <b-form-input
                                                                    v-model="forms.contract.fields.tenant_code"
                                                                    type="text"
                                                                    readonly
                                                                    placeholder="Tenant Code">
                                                                </b-form-input>
                                                            </b-col>
                                                        </b-row>
                                                    </b-form-group>
                                                    <b-form-group>
                                                        <label>* Lessee/Tenant </label>
                                                        <select2
                                                            ref="tenant_id"
                                                            @input="getTenantInfo"
                                                            :allowClear="false"
                                                            :placeholder="'Select Tenants'"
                                                            v-model="forms.contract.fields.tenant_id"
                                                        >
                                                            <option v-for="tenant in options.tenants.items" :key="tenant.tenant_id" :value="tenant.tenant_id">{{tenant.trade_name}}</option>
                                                        </select2>
                                                    </b-form-group>
                                                    <b-form-group>
                                                        <label>Signatory </label>
                                                        <b-form-input
                                                            v-model="forms.contract.fields.contract_signatory"
                                                            type="text"
                                                            readonly
                                                            placeholder="Signatory">
                                                        </b-form-input>
                                                    </b-form-group>
                                                    <b-form-group>
                                                        <label>Billing Address </label>
                                                        <b-form-textarea
                                                            v-model="forms.contract.fields.contract_billing_address"
                                                            type="text"
                                                            readonly
                                                            placeholder="Billing Address">
                                                        </b-form-textarea>
                                                    </b-form-group>
                                                    <b-form-group>
                                                        <label>* Department </label>
                                                        <select2
                                                            ref="department_id"
                                                            :allowClear="false"
                                                            :placeholder="'Select Department'"
                                                            v-model="forms.contract.fields.department_id"
                                                            :reference="'department'"
                                                            @input="isOptionCreating"
                                                        >
                                                            <option value="-1">Create New Department</option>
                                                            <option v-for="department in options.departments.items" :key="department.department_id" :value="department.department_id">{{department.department_desc}}</option>
                                                        </select2>
                                                    </b-form-group>
                                                    <b-form-group>
                                                        <label>* Nature of Business </label>
                                                        <select2
                                                            ref="nature_of_business_id"
                                                            :allowClear="false" 
                                                            :placeholder="'Select Nature of Business'"
                                                            v-model="forms.contract.fields.nature_of_business_id"
                                                            :reference="'natureofbusiness'"
                                                            @input="isOptionCreating"
                                                        >
                                                            <option value="-1">Create New Nature of Business</option>
                                                            <option v-for="nature in options.natureofbusinesses.items" :key="nature.nature_of_business_id" :value="nature.nature_of_business_id">{{nature.nature_of_business_desc}}</option>
                                                        </select2>
                                                    </b-form-group>
                                                    <b-form-group>
                                                        <label>Approved Merchandise </label>
                                                        <b-form-input
                                                            v-model="forms.contract.fields.contract_approved_merch"
                                                            type="text"
                                                            placeholder="Approved Merchandise">
                                                        </b-form-input>
                                                    </b-form-group>
                                                </b-col>
                                                <b-col lg="4">
                                                    <b-form-group>
                                                        <label>* Location </label>
                                                        <select2
                                                            ref="location_id"
                                                            :allowClear="false"
                                                            :placeholder="'Select Location'"
                                                            v-model="forms.contract.fields.location_id"
                                                            :reference="'location'"
                                                            @input="isOptionCreating"
                                                        >
                                                            <option value="-1">Create New Location</option>
                                                            <option v-for="location in options.locations.items" :key="location.location_id" :value="location.location_id">{{location.location_desc}}</option>
                                                        </select2>
                                                    </b-form-group>
                                                    <b-form-group>
                                                        <label>* Contract Types </label>
                                                        <select2
                                                            ref="contract_type_id"
                                                            :allowClear="false"
                                                            :placeholder="'Select Contract Type'"
                                                            v-model="forms.contract.fields.contract_type_id"
                                                            :reference="'contracttype'"
                                                            @input="isOptionCreating"
                                                        >
                                                            <option value="-1">Create New Contract Type</option>
                                                            <option v-for="contract_type in options.contracttypes.items" :key="contract_type.contract_type_id" :value="contract_type.contract_type_id">{{contract_type.contract_type_desc}}</option>
                                                        </select2>
                                                    </b-form-group>
                                                    <b-form-group>
                                                        <label>* Category </label>
                                                        <select2
                                                            ref="category_id"
                                                            :allowClear="false"
                                                            :placeholder="'Select Category'"
                                                            v-model="forms.contract.fields.category_id"
                                                            :reference="'category'"
                                                            @input="isOptionCreating"
                                                        >
                                                            <option value="-1">Create New Category</option>
                                                            <option v-for="category in options.categories.items" :key="category.category_id" :value="category.category_id">{{category.category_desc}}</option>
                                                        </select2>
                                                    </b-form-group>
                                                    <b-form-group>
                                                        <label>* Terms </label>
                                                        <vue-autonumeric
                                                            ref="contract_terms"
                                                            @input="computeDates(true)"
                                                            v-model="forms.contract.fields.contract_terms"
                                                            :class="'form-control text-right'" 
                                                            :options="{minimumValue: 0, modifyValueOnWheel: false, decimalPlaces: 0, emptyInputBehavior: 1}">
                                                        </vue-autonumeric>
                                                    </b-form-group>
                                                    <b-form-group>
                                                        <b-row>
                                                            <b-col lg="6">
                                                                <label>* Commencement Date</label>
                                                                <date-picker 
                                                                    ref="commencement_date"
                                                                    @input="computeDates(true)"
                                                                    v-model="forms.contract.fields.commencement_date" 
                                                                    lang="en" 
                                                                    input-class="form-control mx-input"
                                                                    format="MMMM DD, YYYY"
                                                                    :clearable="false">
                                                                </date-picker>
                                                            </b-col>
                                                            <b-col lg="6">
                                                                <label>* Termination Date</label>
                                                                <date-picker 
                                                                    ref="termination_date"
                                                                    @input="computeDates(false)"
                                                                    v-model="forms.contract.fields.termination_date" 
                                                                    lang="en" 
                                                                    input-class="form-control mx-input"
                                                                    format="MMMM DD, YYYY"
                                                                    :clearable="false">
                                                                </date-picker>
                                                            </b-col>
                                                        </b-row>
                                                    </b-form-group>
                                                    <b-form-group>
                                                        <label>* Start Billing Date </label>
                                                        <date-picker 
                                                            ref="start_billing_date"
                                                            v-model="forms.contract.fields.start_billing_date" 
                                                            lang="en" 
                                                            input-class="form-control mx-input"
                                                            format="MMMM DD, YYYY"
                                                            :clearable="false">
                                                        </date-picker>
                                                    </b-form-group>
                                                    <b-form-group>
                                                        <label>* App Floor Area </label>
                                                        <b-row>
                                                            <b-col lg="6">
                                                                <vue-autonumeric 
                                                                    ref="contract_floor_area"
                                                                    :class="'form-control text-right'" 
                                                                    v-model="forms.contract.fields.contract_floor_area" 
                                                                    :options="{minimumValue: 0,modifyValueOnWheel: false, emptyInputBehavior: 0}">
                                                                </vue-autonumeric> 
                                                            </b-col>
                                                            <span class="align-self-center">Sq. Meter</span>
                                                        </b-row>
                                                    </b-form-group>
                                                </b-col>
                                                <b-col lg="4">
                                                    <b-form-group>
                                                        <b-row>
                                                            <b-col lg="6">
                                                                <label>* Basic Rental </label>
                                                                <vue-autonumeric 
                                                                    ref="contract_fixed_rent"
                                                                    id="fixed_rent"
                                                                    :class="'form-control text-right'" 
                                                                    v-model="forms.contract.fields.contract_fixed_rent" 
                                                                    :options="{minimumValue: 0,modifyValueOnWheel: false, emptyInputBehavior: 0}">
                                                                </vue-autonumeric> 
                                                            </b-col>
                                                            <b-col lg="6">
                                                                <label>Discounted Rental </label>
                                                                <vue-autonumeric 
                                                                    :class="'form-control text-right'" 
                                                                    v-model="forms.contract.fields.contract_discounted_rent" 
                                                                    :options="{minimumValue: 0,modifyValueOnWheel: false, emptyInputBehavior: 0}">
                                                                </vue-autonumeric> 
                                                            </b-col>
                                                        </b-row>
                                                    </b-form-group>
                                                    <b-form-group>
                                                        <b-row>
                                                            <b-col lg="6">
                                                                <label>Advance Rental </label>
                                                                <vue-autonumeric 
                                                                    :class="'form-control text-right'" 
                                                                    v-model="forms.contract.fields.contract_advance_rent" 
                                                                    :options="{minimumValue: 0,modifyValueOnWheel: false, emptyInputBehavior: 0}">
                                                                </vue-autonumeric> 
                                                            </b-col>
                                                            <b-col lg="6">
                                                                <label>Security Deposit </label>
                                                                <vue-autonumeric 
                                                                    :class="'form-control text-right'" 
                                                                    v-model="forms.contract.fields.security_deposit" 
                                                                    :options="{minimumValue: 0,modifyValueOnWheel: false, emptyInputBehavior: 0}">
                                                                </vue-autonumeric> 
                                                            </b-col>
                                                        </b-row>
                                                    </b-form-group>
                                                    <b-form-group>
                                                        <b-row>
                                                            <b-col lg="6">
                                                                <label>Electric Meter Deposit </label>
                                                                <vue-autonumeric 
                                                                    :class="'form-control text-right'" 
                                                                    v-model="forms.contract.fields.power_meter_deposit" 
                                                                    :options="{minimumValue: 0,modifyValueOnWheel: false, emptyInputBehavior: 0}">
                                                                </vue-autonumeric> 
                                                            </b-col>
                                                            <b-col lg="6">
                                                                <label>Water Meter Deposit </label>
                                                                <vue-autonumeric 
                                                                    :class="'form-control text-right'" 
                                                                    v-model="forms.contract.fields.water_meter_deposit" 
                                                                    :options="{minimumValue: 0,modifyValueOnWheel: false, emptyInputBehavior: 0}">
                                                                </vue-autonumeric> 
                                                            </b-col>
                                                        </b-row>
                                                    </b-form-group>
                                                    <b-form-group>
                                                        <b-row>
                                                            <b-col lg="6">
                                                                <label>Construction Deposit </label>
                                                                <vue-autonumeric 
                                                                    :class="'form-control text-right'" 
                                                                    v-model="forms.contract.fields.construction_deposit" 
                                                                    :options="{minimumValue: 0,modifyValueOnWheel: false, emptyInputBehavior: 0}">
                                                                </vue-autonumeric> 
                                                            </b-col>
                                                            <b-col lg="6">
                                                                <label>Escalation % </label>
                                                                <vue-autonumeric 
                                                                    :class="'form-control text-right'" 
                                                                    v-model="forms.contract.fields.contract_escalation_percent" 
                                                                    :options="{minimumValue: 0, maximumValue: 100, modifyValueOnWheel: false, emptyInputBehavior: 0}">
                                                                </vue-autonumeric>
                                                            </b-col>
                                                        </b-row>
                                                    </b-form-group>
                                                    <b-form-group>
                                                        <label>Escalation Notes </label>
                                                        <b-form-input
                                                            v-model="forms.contract.fields.escalation_notes"
                                                            type="text"
                                                            placeholder="Escalation Notes">
                                                        </b-form-input>
                                                    </b-form-group>
                                                    <b-form-group>
                                                        <label>Remarks </label>
                                                        <b-form-textarea
                                                            v-model="forms.contract.fields.contract_remarks"
                                                            type="text"
                                                            :rows="2"
                                                            placeholder="Remarks">
                                                        </b-form-textarea>
                                                    </b-form-group>
                                                </b-col>
                                            </b-row>
                                        </b-tab>
                                        <b-tab title="Schedule" >
                                            <b-row class="mb-2">
                                                <b-col sm="4">
                                                    <h5>Schedule of Payments</h5>
                                                </b-col>
                                                <b-col  sm="4">
                                                    <span></span>
                                                </b-col>
                                                <b-col  sm="4">
                                                    <b-button class="float-right" variant="primary" v-if="this.tables.schedules.items.length == 0" @click="addSchedule()">
                                                            <i class="fa fa-plus-circle"></i> Add Schedule
                                                    </b-button>
                                                </b-col>
                                            </b-row>
                                            <b-table 
                                                responsive
                                                tab="1"
                                                ref="schedules"
                                                small bordered
                                                :fields="tables.schedules.fields"
                                                :items.sync="tables.schedules.items"
                                                show-empty>
                                                <template slot="discounted_rent" slot-scope="data">
                                                    <vue-autonumeric 
                                                        :class="'form-control text-right'"
                                                        v-model="data.item.discounted_rent" 
                                                        :options="{minimumValue: 0,modifyValueOnWheel: false, emptyInputBehavior: 0}">
                                                    </vue-autonumeric>
                                                </template>
                                                <template slot="fixed_rent" slot-scope="data">
                                                    <vue-autonumeric 
                                                        :class="'form-control text-right'"
                                                        v-model="data.item.fixed_rent" 
                                                        :options="{minimumValue: 0,modifyValueOnWheel: false, emptyInputBehavior: 0}">
                                                    </vue-autonumeric>
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
                                                    <b-btn :size="'sm'" variant="primary" @click="addSchedule()">
                                                        <i class="fa fa-plus-circle"></i>
                                                    </b-btn>

                                                    <b-btn :size="'sm'" variant="danger" @click="removeSchedule()">
                                                        <i class="fa fa-times-circle"></i>
                                                    </b-btn>
                                                </template>
                                            </b-table>
                                        </b-tab>
                                        <b-tab title="Charges">
                                            <b-row class="mb-2">
                                                <b-col>
                                                    <h5>Charges</h5>
                                                </b-col>
                                            </b-row>
                                            <b-row>
                                                <b-col sm="12">
                                                    <b-tabs>
                                                        <b-tab title="Utility" >
                                                            <b-row class="mb-2">
                                                                <b-col sm="4">
                                                                    <h5>Utility</h5>
                                                                </b-col>
                                                                <b-col  sm="4">
                                                                    <span></span>
                                                                </b-col>
                                                                <b-col  sm="4">
                                                                    <b-button class="float-right" variant="primary" @click="showModalCharges = true, clearCharges('utilities'), charge_type='utilities'">
                                                                        <i class="fa fa-plus-circle"></i> Add Charges
                                                                    </b-button>
                                                                </b-col>
                                                            </b-row>
                                                            <b-table 
                                                                responsive
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
                                                                <template slot="sort_key" slot-scope="data">
                                                                    <vue-autonumeric 
                                                                        :class="'form-control text-center'"
                                                                        v-model="data.item.sort_key" 
                                                                        :options="{modifyValueOnWheel: false, emptyInputBehavior: 0,
                                                                        decimalPlaces: 0}">
                                                                    </vue-autonumeric>
                                                                </template>
                                                                <template slot="action" slot-scope="data">
                                                                    <b-btn :size="'sm'" variant="danger" @click="removeCharge('utilities', data.index)">
                                                                        <i class="fa fa-times-circle"></i>
                                                                    </b-btn>
                                                                </template>
                                                            </b-table>
                                                        </b-tab>
                                                        <b-tab title="Miscellaneous" >
                                                            <b-row class="mb-2">
                                                                <b-col sm="4">
                                                                    <h5>Miscellaneous</h5>
                                                                </b-col>
                                                            <b-col  sm="4">
                                                                    <span></span>
                                                                </b-col>
                                                                <b-col  sm="4">
                                                                    <b-button class="float-right" variant="primary" @click="showModalCharges = true, clearCharges('miscellaneous'),charge_type='miscellaneous'">
                                                                        <i class="fa fa-plus-circle"></i> Add Charges
                                                                    </b-button>
                                                                </b-col>
                                                            </b-row>
                                                            <b-table 
                                                                responsive
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
                                                                <template slot="sort_key" slot-scope="data">
                                                                    <vue-autonumeric 
                                                                        :class="'form-control text-center'"
                                                                        v-model="data.item.sort_key" 
                                                                        :options="{modifyValueOnWheel: false, emptyInputBehavior: 0,
                                                                        decimalPlaces: 0}">
                                                                    </vue-autonumeric>
                                                                </template>
                                                                <template slot="action" slot-scope="data">
                                                                    <b-btn :size="'sm'" variant="danger" @click="removeCharge('miscellaneous', data.index)">
                                                                        <i class="fa fa-times-circle"></i>
                                                                    </b-btn>
                                                                </template>
                                                            </b-table>
                                                        </b-tab>
                                                        <b-tab title="Other" >
                                                            <b-row class="mb-2">
                                                                <b-col sm="4">
                                                                    <h5>Other</h5>
                                                                </b-col>
                                                                <b-col sm="4">
                                                                    <span></span>
                                                                </b-col>
                                                                <b-col  sm="4">
                                                                    <b-button class="float-right" variant="primary" @click="showModalCharges = true, clearCharges('other'),charge_type='other'">
                                                                        <i class="fa fa-plus-circle"></i> Add Charges
                                                                    </b-button>
                                                                </b-col>
                                                            </b-row>
                                                            <b-table
                                                                responsive 
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
                                                                <template slot="sort_key" slot-scope="data">
                                                                    <vue-autonumeric 
                                                                        :class="'form-control text-center'"
                                                                        v-model="data.item.sort_key" 
                                                                        :options="{modifyValueOnWheel: false, emptyInputBehavior: 0,
                                                                        decimalPlaces: 0}">
                                                                    </vue-autonumeric>
                                                                </template>
                                                                <template slot="action" slot-scope="data">
                                                                    <b-btn :size="'sm'" variant="danger" @click="removeCharge('other', data.index)">
                                                                        <i class="fa fa-times-circle"></i>
                                                                    </b-btn>
                                                                </template>
                                                            </b-table>
                                                        </b-tab>
                                                    </b-tabs>
                                                </b-col>
                                            </b-row>
                                        </b-tab>
                                    </b-tabs>
                                </b-col>
                            </b-row>
                            <b-row class="pull-right mt-2">
                                <b-col sm="12">
                                    <b-button 
                                        :disabled="forms.contract.isSaving" 
                                        variant="primary" 
                                        @click="onContractEntry">
                                        <icon v-if="forms.contract.isSaving" name="sync" spin></icon>
                                        <i class="fa fa-check"></i>
                                        Save
                                    </b-button>
                                    <b-button variant="secondary" @click="showEntry=false">Close</b-button>
                                </b-col>
                            </b-row>
                        </b-form>
                    </b-card>
                </b-col>
            </b-row>
        </div>
        <b-modal 
            v-model="showModalCharges"
            :noCloseOnEsc="true"
            :noCloseOnBackdrop="true"
        >
            <div slot="modal-title">
                Charges
            </div>
            <b-col lg=12>
                <b-row class='mb-2'>
                    <b-col  sm="8">
                        <span></span>
                    </b-col>

                    <b-col  sm="4">
                        <b-form-input 
                            v-model="filters.charges.criteria"
                            type="text" 
                            placeholder="Search">
                        </b-form-input>
                    </b-col>
                </b-row>
                <b-table 
                    responsive
                    small bordered
                    :filter="filters.charges.criteria"
                    :fields="tables.charges.fields"
                    :items.sync="tables.charges.items"
                    show-empty>
                    <template slot="is_selected" slot-scope="data">
                        <input type="checkbox" v-model="data.item.is_selected">
                    </template>
                    <template slot="HEAD_is_selected" slot-scope="data">
                        <input @click="toggleSelectAll()" type="checkbox" v-model="is_check_all">
                    </template>
                </b-table>
                <b-pagination
                    :align="'right'"
                    :total-rows="paginations.charges.totalRows"
                    :per-page="paginations.charges.perPage"
                    v-model="paginations.charges.currentPage" />
            </b-col>
            <div slot="modal-footer">
                <b-button variant="primary" @click="addCharges(charge_type)">
                    <i class="fa fa-check"></i>
                    Add
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
                Delete Contract
            </div>
            <b-col lg=12>
                Are you sure you want to delete this contract?
            </b-col>
            <div slot="modal-footer">
                <b-button :disabled="forms.contract.isSaving" variant="primary" @click="onContractDelete">
                    <icon v-if="forms.contract.isSaving" name="sync" spin></icon>
                    <i class="fa fa-check"></i>
                    OK
                </b-button>
                <b-button variant="secondary" @click="showModalDelete=false">Close</b-button>            
            </div>
        </b-modal>

        <!-- department modal -->
        <b-modal 
                v-model="showModalDepartment"
                :noCloseOnEsc="true"
                :noCloseOnBackdrop="true"
                @shown="focusElement('department_code')"
            >
            
            <div slot="modal-title"> <!-- modal title -->
                Department Entry - {{entryMode}}
            </div> <!-- modal title -->

            <b-col lg=12> <!-- modal body -->
                <b-form @keydown="resetFieldStates('department')" autocomplete="off">
                    <b-form-group>
                        <label for="department_code">* Department Code</label>
                        <b-form-input
                            id="department_code"
                            v-model="forms.department.fields.department_code"
                            type="text"
                            placeholder="Department Code"
                            ref="department_code">
                        </b-form-input>
                    </b-form-group>
                    <b-form-group>
                        <label>* Department Desc</label>
                        <b-form-input
                            ref="department_desc"
                            id="department_desc"
                            v-model="forms.department.fields.department_desc"
                            type="text"
                            placeholder="Department Description">
                        </b-form-input>
                    </b-form-group>
                </b-form>
            </b-col> <!-- modal body -->

            <div slot="modal-footer"><!-- modal footer buttons -->
                <b-button :disabled="forms.department.isSaving" variant="primary" @click="saveOption('department')">
                    <icon v-if="forms.department.isSaving" name="sync" spin></icon>
                    <i class="fa fa-check"></i>
                    Save
                </b-button>
                <b-button variant="secondary" @click="showModalDepartment=false">Close</b-button>
            </div> <!-- modal footer buttons -->

        </b-modal> <!--department modal -->
        


        <!--nature of business modal -->
        <b-modal 
                v-model="showModalNature"
                :noCloseOnEsc="true"
                :noCloseOnBackdrop="true"
                @shown="focusElement('nature_of_business_code')"
            >
                
            <div slot="modal-title"> <!-- modal title -->
                Nature Of Business Entry - {{entryMode}}
            </div> <!-- modal title -->

            <b-col lg=12> <!-- modal body -->
                <b-form @keydown="resetFieldStates('natureofbusiness')" autocomplete="off">
                    <b-form-group>
                        <label for="nature_of_business_code">* Nature Of Business Code</label>
                        <b-form-input
                            ref="nature_of_business_code"
                            id="nature_of_business_code"
                            v-model="forms.natureofbusiness.fields.nature_of_business_code"
                            type="text"
                            placeholder="Nature Of Business Code">
                        </b-form-input>
                    </b-form-group>
                    <b-form-group>
                        <label>* Nature Of Business Desc</label>
                        <b-form-input
                            ref="nature_of_business_desc"
                            id="nature_of_business_desc"
                            v-model="forms.natureofbusiness.fields.nature_of_business_desc"
                            type="text"
                            placeholder="Nature Of Business Description">
                        </b-form-input>
                    </b-form-group>
                </b-form>
            </b-col> <!-- modal body -->

            <div slot="modal-footer"><!-- modal footer buttons -->
                <b-button :disabled="forms.natureofbusiness.isSaving" variant="primary" @click="saveOption('natureofbusiness')">
                    <icon v-if="forms.natureofbusiness.isSaving" name="sync" spin></icon>
                    <i class="fa fa-check"></i>
                    Save
                </b-button>
                <b-button variant="secondary" @click="showModalNature=false">Close</b-button>
            </div> <!-- modal footer buttons -->
        </b-modal> <!--nature of business modal -->

        <!-- category modal -->
        <b-modal 
            v-model="showModalCategory"
            :noCloseOnEsc="true"
            :noCloseOnBackdrop="true"
            @shown="focusElement('category_code')"
        >
            <div slot="modal-title">
                Category Entry - {{entryMode}}
            </div>
            <b-col lg=12>
                <b-form @keydown="resetFieldStates('category')" autocomplete="off">
                    <b-form-group>
                        <label for="category_code">* Category Code</label>
                        <b-form-input
                            ref="category_code"
                            id="category_code"
                            v-model="forms.category.fields.category_code"
                            type="text"
                            placeholder="Category Code">
                        </b-form-input>
                    </b-form-group>
                    <b-form-group>
                        <label>* Category Desc</label>
                        <b-form-input
                            ref="category_desc"
                            id="category_desc"
                            v-model="forms.category.fields.category_desc"
                            type="text"
                            placeholder="Category Description">
                        </b-form-input>
                    </b-form-group>
                </b-form>
            </b-col>
            <div slot="modal-footer">
                <b-button :disabled="forms.category.isSaving" variant="primary" @click="saveOption('category')">
                    <icon v-if="forms.category.isSaving" name="sync" spin></icon>
                    <i class="fa fa-check"></i>
                    Save
                </b-button>
                <b-button variant="secondary" @click="showModalCategory=false">Close</b-button>            
            </div>
        </b-modal> <!-- category modal -->

        <!-- contract type modal -->
        <b-modal 
                v-model="showModalContractType"
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
                <b-button :disabled="forms.contracttype.isSaving" variant="primary" @click="saveOption('contracttype')">
                    <icon v-if="forms.contracttype.isSaving" name="sync" spin></icon>
                    <i class="fa fa-check"></i>
                    Save
                </b-button>
                <b-button variant="secondary" @click="showModalContractType=false">Close</b-button>
            </div> <!-- modal footer buttons -->
        </b-modal><!-- contract type modal -->

        <!-- location modal -->
        <b-modal 
                v-model="showModalLocation"
                :noCloseOnEsc="true"
                :noCloseOnBackdrop="true"
                @shown="focusElement('location_code')"
            >
            
            <div slot="modal-title"> <!-- modal title -->
                Location Entry - {{entryMode}}
            </div> <!-- modal title -->

            <b-col lg=12> <!-- modal body -->
                <b-form @keydown="resetFieldStates('location')" autocomplete="off">
                    <b-form-group>
                        <label for="location_code">* Location Code</label>
                        <b-form-input
                            ref="location_code"
                            id="location_code"
                            v-model="forms.location.fields.location_code"
                            type="text"
                            placeholder="Location Code">
                        </b-form-input>
                    </b-form-group>
                    <b-form-group>
                        <label>* Location Desc</label>
                        <b-form-input
                            ref="location_desc"
                            id="location_desc"
                            v-model="forms.location.fields.location_desc"
                            type="text"
                            placeholder="Location Description">
                        </b-form-input>
                    </b-form-group>
                </b-form>
            </b-col> <!-- modal body -->

            <div slot="modal-footer"><!-- modal footer buttons -->
                <b-button :disabled="forms.location.isSaving" variant="primary" @click="saveOption('location')">
                    <icon v-if="forms.location.isSaving" name="sync" spin></icon>
                    <i class="fa fa-check"></i>
                    Save
                </b-button>
                <b-button variant="secondary" @click="showModalLocation=false">Close</b-button>
            </div> <!-- modal footer buttons -->

        </b-modal>

    </div>
</template>

<script>
export default {
    name: 'contracts',
    data () {
        return {
            entryMode: 'Add',
            showEntry: false, //if true show entry
            showModalDelete: false,
            showModalCharges: false,
            showModalDepartment: false,
            showModalNature: false,
            showModalCategory: false,
            showModalContractType: false,
            showModalLocation: false,
            options: {
                tenants: {
                    items: []
                },
                departments: {
                    items: []
                },
                natureofbusinesses: {
                    items: []
                },
                locations: {
                    items: []
                },
                contracttypes: {
                    items: []
                },
                categories: {
                    items: []
                },
                months: {
                    items: []
                }
            },
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
                        contract_terms: null,
                        commencement_date: new Date(),
                        termination_date: new Date(),
                        start_billing_date: new Date(),
                        contract_floor_area: 0,
                        contract_fixed_rent: 0,
                        contract_discounted_rent: 0,
                        contract_advance_rent: 0,
                        contract_escalation_percent: 0,
                        security_deposit: 0,
                        power_meter_deposit: 0,
                        water_meter_deposit: 0,
                        construction_deposit: 0,
                        escalation_notes: null,
                        contract_remarks: null,
                        schedules: [],
                        utilities: [],
                        miscellaneous: [],
                        other: [],
                    }
                },
                department : {
                    isSaving: false,
                    isDeleting: false,
                    fields: {
                        department_id: null,
                        department_code: null,
                        department_desc: null
                    }
                },
                natureofbusiness : {
                    isSaving: false,
                    fields: {
                        nature_of_business_id: null,
                        nature_of_business_code: null,
                        nature_of_business_desc: null
                    }
                },
                contracttype : {
                    isSaving: false,
                    fields: {
                        contract_type_id: null,
                        contract_type_code: null,
                        contract_type_desc: null
                    }
                },
                category : {
                    isSaving: false,
                    fields: {
                        category_id: null,
                        category_code: null,
                        category_desc: null
                    }
                },
                location : {
                    isSaving: false,
                    fields: {
                        location_id: null,
                        location_code: null,
                        location_desc: null
                    }
                }

            },
            tables: {
                contracts: {
                    fields:[
                        {
                            key: 'contract_id',
                            thClass: 'd-none',
                            tdClass: 'd-none'
                        },
                        {
                            key:'row_data',
                            label: '',
                            tdClass: '',
                            thStyle: {width: '2%'}
                        },
                        {
                            key:'contract_no',
                            label: 'Contract No',
                            tdClass: 'align-middle',
                            sortable: true
                        },
                        {
                            key:'trade_name',
                            label: 'Trade Name',
                            tdClass: 'align-middle',
                            sortable: true
                        },
                        {
                            key:'department_desc',
                            label:'Department',
                            tdClass: 'align-middle',
                        },
                        {
                            key:'location_desc',
                            label:'Location',
                            tdClass: 'align-middle',
                        },
                        {
                            key:'contract_type_desc',
                            label:'Contract Type',
                            tdClass: 'align-middle',
                        },
                        {
                            key:'category_desc',
                            label:'Category',
                            tdClass: 'align-middle',
                        },
                        {
                            key:'action',
                            label:'Action',
                            thClass: 'text-center',
                            thStyle: {width: '75px'},
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
                            tdClass: 'text-center',
                            thStyle: {width: '5px'}
                        },
                        {
                            key:'charge_code',
                            label: 'Charge Code',
                            tdClass: 'align-middle',
                        },
                        {
                            key:'charge_desc',
                            label: 'Description',
                            tdClass: 'align-middle',
                        },
                    ],
                    items:[]
                },
                schedules: {
                    fields: [
                        {
                            key: 'count',
                            label: '',
                            tdClass: 'align-middle'
                        },
                        {
                            key: 'month_name',
                            label: 'App Month',
                            thStyle: {width: '8%'},
                            tdClass: 'align-middle'
                        },
                        {
                            key: 'app_year',
                            label: 'Year',
                            tdClass: 'align-middle',
                            thStyle: {width: '5%'},
                        },
                        {
                            key: 'discounted_rent',
                            label: 'Disc Rent',
                            thClass: 'text-right',
                            tdClass: 'text-right align-middle',
                            thStyle: {width: '12%'}
                        },
                        {
                            key: 'fixed_rent',
                            label: 'Basic Rent',
                            thClass: 'text-right',
                            tdClass: 'text-right align-middle',
                            thStyle: {width: '12%'}
                        },
                        {
                            key: 'escalation_percent',
                            label: 'Escalation %',
                            thClass: 'text-right',
                            tdClass: 'text-right align-middle',
                            thStyle: {width: '10%'}
                        },
                        {
                            key: 'discounted_amount_due',
                            label: 'Disc Amount Due',
                            thClass: 'text-right',
                            tdClass: 'text-right align-middle',
                            thStyle: {width: '12%'},
                            formatter: (value, key, item) => {
                                item.discounted_amount_due = Number(item.discounted_rent) + (Number(item.discounted_rent) * (Number(item.escalation_percent)/100))
                                return this.formatNumber(item.discounted_amount_due)
                                
                            }
                        },
                        {
                            key: 'amount_due',
                            label: 'Amount Due',
                            thClass: 'text-right',
                            tdClass: 'text-right align-middle',
                            thStyle: {width: '12%'},
                            formatter: (value, key, item) => {
                                item.amount_due = Number(item.fixed_rent) + (Number(item.fixed_rent) * (Number(item.escalation_percent)/100))
                                return this.formatNumber(item.amount_due)
                                
                            }
                        },
                        {
                            key: 'is_vatted',
                            label: 'Is Vatted?',
                            thClass: 'text-center',
                            tdClass: 'text-center align-middle',
                            thStyle: {width: '7%'},
                        },
                        {
                            key: 'contract_schedule_notes',
                            label: 'Notes'
                        },
                        {
                            key: 'action',
                            label: 'Action',
                            thClass: 'text-center',
                            thStyle: {width: '75px'},
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
                            tdClass: 'align-middle'
                        },
                        {
                            key: 'contract_rate',
                            label: 'Rate',
                            thClass: 'text-right',
                            tdClass: 'align-middle text-right'
                        },
                        {
                            key: 'contract_default_reading',
                            label: 'Default Reading',
                            thClass: 'text-right',
                            tdClass: 'text-right align-middle'
                        },
                        {
                            key: 'contract_is_vatted',
                            label: 'Is Vatted?',
                            thClass: 'text-center',
                            tdClass: 'text-center align-middle'
                        },
                        {
                            key: 'contract_notes',
                            label: 'Notes'
                        },
                        {
                            key: 'sort_key',
                            label: 'Sort',
                            thClass: 'text-center',
                            thStyle: {width: '75px'}
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
                            tdClass: 'align-middle'
                        },
                        {
                            key: 'contract_rate',
                            label: 'Rate',
                            thClass: 'text-right',
                            tdClass: 'align-middle text-right'
                        },
                        {
                            key: 'contract_default_reading',
                            label: 'Default Reading',
                            thClass: 'text-right',
                            tdClass: 'text-right align-middle'
                        },
                        {
                            key: 'contract_is_vatted',
                            label: 'Is Vatted?',
                            thClass: 'text-center',
                            tdClass: 'text-center align-middle'
                        },
                        {
                            key: 'contract_notes',
                            label: 'Notes'
                        },
                        {
                            key: 'sort_key',
                            label: 'Sort',
                            thClass: 'text-center',
                            thStyle: {width: '75px'}
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
                            tdClass: 'align-middle'
                        },
                        {
                            key: 'contract_rate',
                            label: 'Rate',
                            thClass: 'text-right',
                            tdClass: 'align-middle text-right'
                        },
                        {
                            key: 'contract_default_reading',
                            label: 'Default Reading',
                            thClass: 'text-right',
                            tdClass: 'text-right align-middle'
                        },
                        {
                            key: 'contract_is_vatted',
                            label: 'Is Vatted?',
                            thClass: 'text-center',
                            tdClass: 'text-center align-middle'
                        },
                        {
                            key: 'contract_notes',
                            label: 'Notes'
                        },
                        {
                            key: 'sort_key',
                            label: 'Sort',
                            thClass: 'text-center',
                            thStyle: {width: '75px'}
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
                }
            },
            filters: {
                contracts: {
                    criteria: null
                },
                charges: {
                    criteria: null
                }
            },
            paginations: {
                contracts: {
                    totalRows: 0,
                    currentPage: 1,
                    perPage: 10
                },
                charges: {
                    totalRows: 0,
                    currentPage: 1,
                    perPage: 10
                }
            },
            counter: 0,
            app_year: null,
            charge_type: null,
            tabIndex: 0,
            contract_id: null,
            is_check_all: 0,
            row: []
        }
    },
    methods:{
        onContractEntry () {
            this.forms.contract.fields.schedules = this.tables.schedules.items
            this.forms.contract.fields.utilities = this.tables.utilities.items
            this.forms.contract.fields.miscellaneous = this.tables.miscellaneous.items
            this.forms.contract.fields.other = this.tables.other.items

            if(this.entryMode == 'Add'){
                this.createEntity('contract', false, 'contracts', true)
            }
            else{
                this.updateEntity('contract', 'contract_id', false, this.row, true)
            }
        },
        onContractDelete(){
            this.deleteEntity('contract', this.contract_id, true, 'contracts', 'contract_id')
        },
        async setDelete(data){
            if(await this.checkIfUsed('contract', data.item.contract_id) == true){
                this.$notify({
                    type: 'error',
                    group: 'notification',
                    title: 'Error!',
                    text: "Unable to delete, this record is being used by other transactions."
                })
                return
            }
            this.contract_id = data.item.contract_id
            this.showModalDelete = true
        },
        setUpdate(data){
            this.row = data.item
            this.fillEntityForm('contract', data.item.contract_id)
            this.$http.get('/api/contracts/sc/'+ data.item.contract_id,{
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
                this.showEntry=true
                this.entryMode='Edit'
                this.counter = this.tables.schedules.items.length
            }).catch(error => {
              if (!error.response) return
              console.log(error)
            })
        },
        addSchedule(){
            try {
                if(this.forms.contract.fields.contract_terms >= this.counter){
                    var fixed_rent = 0
                    var discounted_rent = 0
                    var start = new Date(this.forms.contract.fields.start_billing_date)
                    if(this.counter == 0){
                        this.app_year = start.getFullYear()
                        fixed_rent = this.forms.contract.fields.contract_fixed_rent
                        discounted_rent = this.forms.contract.fields.contract_discounted_rent
                    }
                    else{
                        var prev_schedule = this.tables.schedules.items.find(s => s.count == this.counter)
                        fixed_rent = prev_schedule.amount_due
                        discounted_rent = prev_schedule.discounted_amount_due
                    }
                    var month;
                    var month_value = start.getMonth() + 1
                    var add_year = false
                    var escalation_percent = 0
                    var month_id = (month_value + this.counter) % 12
                    var discounted_amount_due = discounted_rent
                    var amount_due = fixed_rent

                    if(month_id == 0){
                        month = this.options.months.items.find(d => d.month_id === 12)
                        add_year = true
                    }
                    else{
                        month = this.options.months.items.find(d => d.month_id === month_id)
                    }

                    this.counter++;
                    if(this.counter % 12 == 1){
                        if(this.counter != 1){
                            escalation_percent = this.forms.contract.fields.contract_escalation_percent
                        }
                    }

                    this.tables.schedules.items.push({
                        count: this.counter,
                        month_name:month.month_name,
                        month_id:month.month_id,
                        app_year:this.app_year,
                        discounted_rent:discounted_rent,
                        fixed_rent:fixed_rent,
                        escalation_percent:escalation_percent,
                        discounted_amount_due:discounted_amount_due,
                        amount_due:amount_due,
                        is_vatted:1,
                        contract_schedule_notes:''
                    })

                    if(add_year){
                        this.app_year++
                    }   
                }
                else{
                    return
                }
            } catch(e)
            {
                console.log(e)
            }
        },
        removeSchedule(){
            var start = new Date(this.forms.contract.fields.start_billing_date)
            var month_value = start.getMonth() + 1

            this.tables.schedules.items.splice(this.tables.schedules.items.length - 1, 1)
            this.counter--
            var month_id = (month_value + this.counter) % 12

            if(month_id == 0){
                console.log('minus year')
                this.app_year--
            }
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
                            contract_notes:'',
                            sort_key:charge.sort
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
        clearCharges(charge_type){
            this.tables.charges.items.forEach(charge => {
                charge.is_selected = false
            })
            this.is_check_all = false
        },
        getTenantInfo: function (value, data) {
            if(data.length > 0){
                var tenant = this.options.tenants.items[data[0].element.index]
                this.forms.contract.fields.tenant_code = tenant.tenant_code;
                this.forms.contract.fields.contract_signatory = tenant.contact_person;
                this.forms.contract.fields.contract_billing_address = tenant.billing_address;
            }
        },
        toggleSelectAll(){
            if(this.is_check_all == 0){
                this.tables.charges.items.forEach(charge => {
                    charge.is_selected = true
                })
                this.is_check_all = 1
            }
            else{
                this.tables.charges.items.forEach(charge => {
                    charge.is_selected = false
                })
                this.is_check_all = 0
            }
        },
        isOptionCreating: function(value, data, reference){
            if(value == -1){
                if(reference == 'department'){
                    //show department entry modal
                    this.showModalDepartment = true
                    this.forms.contract.fields.department_id = "null"
                }
                else if(reference == 'natureofbusiness'){
                    this.showModalNature = true
                    this.forms.contract.fields.nature_of_business_id = "null"
                }
                else if(reference == 'contracttype'){
                    this.showModalContractType = true
                    this.forms.contract.fields.contract_type_id = "null"
                }
                else if(reference == 'category'){
                    this.showModalCategory = true
                    this.forms.contract.fields.category_id = "null"
                }
                else if(reference == 'location'){
                    this.showModalLocation = true
                    this.forms.contract.fields.location_id = "null"
                }
            }
        },
        saveOption(reference){
            if(reference == 'department'){
                this.createOptionsEntity('department', 'showModalDepartment', 'departments', 'contract','department_id')
            }
            else if (reference == 'natureofbusiness'){
                this.createOptionsEntity('natureofbusiness', 'showModalNature', 'natureofbusinesses','contract','nature_of_business_id')
            }
            else if (reference == 'contracttype'){
                this.createOptionsEntity('contracttype', 'showModalContractType', 'contracttypes','contract','contract_type_id')
            }
            else if (reference == 'category'){
                this.createOptionsEntity('category', 'showModalCategory', 'categories','contract','category_id')
            }
            else if (reference == 'location'){
                this.createOptionsEntity('location', 'showModalLocation', 'locations','contract','location_id')
            }
        },
        computeDates(is_commencement){
            if(is_commencement){
                this.forms.contract.fields.termination_date = moment(this.forms.contract.fields.commencement_date).add(this.forms.contract.fields.contract_terms, 'months').subtract(1,'days').format('MMMM DD, YYYY')
            }
            else{
                this.forms.contract.fields.commencement_date = moment(this.forms.contract.fields.termination_date).subtract(this.forms.contract.fields.contract_terms, 'months').add(1,'days').format('MMMM DD, YYYY')
            }
            
        }
    },
    created () {
        this.fillTableList('contracts')
        this.fillTableList('charges')
        this.fillOptionsList('tenants')
        this.fillOptionsList('departments')
        this.fillOptionsList('natureofbusinesses')
        this.fillOptionsList('locations')
        this.fillOptionsList('contracttypes')
        this.fillOptionsList('categories')
        this.fillOptionsList('months')
    },
    watch: {
        showEntry: function (showEntry) {
            if(showEntry){
                let self = this
                Vue.nextTick(function(){
                    self.focusElement('tenant_id')
                })
            }
        },
    }
  }
</script>