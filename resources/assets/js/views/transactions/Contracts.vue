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
                                        @click="showEntry = true, entryMode='Add', tables.schedules.items=[], tables.utilities.items=[], tables.miscellaneous.items=[], tables.other.items=[], tabIndex=0, clearFields('contract'), forms.contract.fields.contract_terms = 1, counter = 0 ">
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
                                                            :allowClear="false"
                                                            :placeholder="'Select Department'"
                                                            v-model="forms.contract.fields.department_id"
                                                        >
                                                            <option v-for="department in options.departments.items" :key="department.department_id" :value="department.department_id">{{department.department_desc}}</option>
                                                        </select2>
                                                    </b-form-group>
                                                    <b-form-group>
                                                        <label>* Nature of Business </label>
                                                        <select2
                                                            :allowClear="false" 
                                                            :placeholder="'Select Nature of Business'"
                                                            v-model="forms.contract.fields.nature_of_business_id"
                                                        >
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
                                                            :allowClear="false"
                                                            :placeholder="'Select Location'"
                                                            v-model="forms.contract.fields.location_id"
                                                        >
                                                            <option v-for="location in options.locations.items" :key="location.location_id" :value="location.location_id">{{location.location_desc}}</option>
                                                        </select2>
                                                    </b-form-group>
                                                    <b-form-group>
                                                        <label>* Contract Types </label>
                                                        <select2
                                                            :allowClear="false"
                                                            :placeholder="'Select Contract Type'"
                                                            v-model="forms.contract.fields.contract_type_id"
                                                        >
                                                            <option v-for="contract_type in options.contracttypes.items" :key="contract_type.contract_type_id" :value="contract_type.contract_type_id">{{contract_type.contract_type_desc}}</option>
                                                        </select2>
                                                    </b-form-group>
                                                    <b-form-group>
                                                        <label>* Category </label>
                                                        <select2
                                                            :allowClear="false"
                                                            :placeholder="'Select Category'"
                                                            v-model="forms.contract.fields.category_id"
                                                        >
                                                            <option v-for="category in options.categories.items" :key="category.category_id" :value="category.category_id">{{category.category_desc}}</option>
                                                        </select2>
                                                    </b-form-group>
                                                    <b-form-group>
                                                        <label>* Terms </label>
                                                        <vue-autonumeric 
                                                            v-model="forms.contract.fields.contract_terms"
                                                            :class="'form-control text-right'" 
                                                            :options="{minimumValue: 0, modifyValueOnWheel: false, emptyInputBehavior: 1, decimalPlaces: 0}">
                                                        </vue-autonumeric>
                                                    </b-form-group>
                                                    <b-form-group>
                                                        <b-row>
                                                            <b-col lg="6">
                                                                <label>* Commencement Date</label>
                                                                <date-picker 
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
                                                        <label>* Basic Rental </label>
                                                        <vue-autonumeric 
                                                            id="fixed_rent"
                                                            :class="'form-control text-right'" 
                                                            v-model="forms.contract.fields.contract_fixed_rent" 
                                                            :options="{minimumValue: 0,modifyValueOnWheel: false, emptyInputBehavior: 0}">
                                                        </vue-autonumeric> 
                                                    </b-form-group>
                                                    <b-form-group>
                                                        <label>Discounted Rental </label>
                                                        <vue-autonumeric 
                                                            :class="'form-control text-right'" 
                                                            v-model="forms.contract.fields.contract_discounted_rent" 
                                                            :options="{minimumValue: 0,modifyValueOnWheel: false, emptyInputBehavior: 0}">
                                                        </vue-autonumeric> 
                                                    </b-form-group>
                                                    <b-form-group>
                                                        <label>Advance Rental </label>
                                                        <vue-autonumeric 
                                                            :class="'form-control text-right'" 
                                                            v-model="forms.contract.fields.contract_advance_rent" 
                                                            :options="{minimumValue: 0,modifyValueOnWheel: false, emptyInputBehavior: 0}">
                                                        </vue-autonumeric> 
                                                    </b-form-group>
                                                    <b-form-group>
                                                        <label>Security Deposit </label>
                                                        <vue-autonumeric 
                                                            :class="'form-control text-right'" 
                                                            v-model="forms.contract.fields.security_deposit" 
                                                            :options="{minimumValue: 0,modifyValueOnWheel: false, emptyInputBehavior: 0}">
                                                        </vue-autonumeric> 
                                                    </b-form-group>
                                                    <b-form-group>
                                                        <label>Electric Meter Deposit </label>
                                                        <vue-autonumeric 
                                                            :class="'form-control text-right'" 
                                                            v-model="forms.contract.fields.power_meter_deposit" 
                                                            :options="{minimumValue: 0,modifyValueOnWheel: false, emptyInputBehavior: 0}">
                                                        </vue-autonumeric> 
                                                    </b-form-group>
                                                    <b-form-group>
                                                        <label>Water Meter Deposit </label>
                                                        <vue-autonumeric 
                                                            :class="'form-control text-right'" 
                                                            v-model="forms.contract.fields.water_meter_deposit" 
                                                            :options="{minimumValue: 0,modifyValueOnWheel: false, emptyInputBehavior: 0}">
                                                        </vue-autonumeric> 
                                                    </b-form-group>
                                                    <b-form-group>
                                                        <label>Construction Deposit </label>
                                                        <vue-autonumeric 
                                                            :class="'form-control text-right'" 
                                                            v-model="forms.contract.fields.construction_deposit" 
                                                            :options="{minimumValue: 0,modifyValueOnWheel: false, emptyInputBehavior: 0}">
                                                        </vue-autonumeric> 
                                                    </b-form-group>
                                                    <b-form-group>
                                                        <label>Escalation % </label>
                                                        <vue-autonumeric 
                                                            :class="'form-control text-right'" 
                                                            v-model="forms.contract.fields.contract_escalation_percent" 
                                                            :options="{minimumValue: 0, maximumValue: 100, modifyValueOnWheel: false, emptyInputBehavior: 0}">
                                                        </vue-autonumeric>
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
                                                small bordered
                                                :fields="tables.schedules.fields"
                                                :items.sync="tables.schedules.items"
                                                show-empty>
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
                        contract_terms: 1,
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
                        schedules: [],
                        utilities: [],
                        miscellaneous: [],
                        other: [],
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
                        contract_advance_rent: null,
                        contract_escalation_percent: null,
                        security_deposit: null,
                        power_meter_deposit: null,
                        water_meter_deposit: null,
                        construction_deposit: null
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
                        contract_advance_rent: null,
                        contract_escalation_percent: null,
                        security_deposit: null,
                        power_meter_deposit: null,
                        water_meter_deposit: null,
                        construction_deposit: null
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
                            key: 'fixed_rent',
                            label: 'Basic Rent',
                            thClass: 'text-right',
                            tdClass: 'text-right align-middle',
                            thStyle: {width: '15%'}
                        },
                        {
                            key: 'escalation_percent',
                            label: 'Escalation %',
                            thClass: 'text-right',
                            tdClass: 'text-right align-middle',
                            thStyle: {width: '15%'}
                        },
                        {
                            key: 'amount_due',
                            label: 'Amount Due',
                            thClass: 'text-right',
                            tdClass: 'text-right align-middle',
                            thStyle: {width: '15%'},
                            formatter: (value, key, item) => {
                                item.amount_due = Number(item.fixed_rent) + (Number(item.fixed_rent) * (Number(item.escalation_percent)/100))
                                return this.formatNumber(item.amount_due)
                                
                            }
                        },
                        {
                            key: 'is_vatted',
                            label: 'Is Vatted?',
                            thClass: 'text-center',
                            tdClass: 'text-center align-middle'
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
                this.createEntity('contract', false, 'contracts')
            }
            else{
                this.updateEntity('contract', 'contract_id', false, this.row)
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
                    var start = new Date(this.forms.contract.fields.start_billing_date)
                    if(this.counter == 0){
                        this.app_year = start.getFullYear()
                        fixed_rent = this.forms.contract.fields.contract_fixed_rent
                    }
                    else{
                        var prev_schedule = this.tables.schedules.items.find(s => s.count == this.counter)
                        fixed_rent = prev_schedule.amount_due
                    }
                    var month;
                    var month_value = start.getMonth() + 1
                    var add_year = false
                    var escalation_percent = 0
                    var month_id = (month_value + this.counter) % 12
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
                        fixed_rent:fixed_rent,
                        escalation_percent:escalation_percent,
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
                            contract_notes:'',
                            sort_key:0
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
                    self.focusElement('tenant_id', true)
                })
            }
        },
    }
  }
</script>