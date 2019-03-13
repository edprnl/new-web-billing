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
                                Payment List
                                <small class="font-italic">List of all payments.</small></span>
                        </h5>
                        <b-row class="mb-2">
                            <b-col  sm="2">
                                    <b-button variant="primary" @click="resetFieldStates('payment'),clearFields('payment'),entryMode = 'Add', showEntry = true, tables.payment_details.items = []">
                                            <i class="fa fa-plus-circle"></i> Create New Payment
                                    </b-button>
                            </b-col>
                            <b-col sm="1">
                                <label class="col-form-label">From:</label>
                            </b-col>
                            <b-col sm="2">
                                <date-picker 
                                    @input="filterPayments()"
                                    v-model="date_from" 
                                    lang="en" 
                                    input-class="form-control mx-input"
                                    format="MMMM DD, YYYY"
                                    :clearable="false">
                                </date-picker>
                            </b-col>
                            <b-col sm="1">
                                <label class="col-form-label">To:</label>
                            </b-col>
                            <b-col sm="2">
                                <date-picker
                                    @input="filterPayments()"
                                    v-model="date_to" 
                                    lang="en" 
                                    input-class="form-control mx-input"
                                    format="MMMM DD, YYYY"
                                    :clearable="false">
                                </date-picker>
                            </b-col>
                            <b-col  sm="4">
                                <b-form-input 
                                            v-model="filters.payments.criteria" 
                                            type="text" 
                                            placeholder="Search">
                                </b-form-input>
                            </b-col>
                        </b-row>
                        
                        <b-row>
                            <b-col sm="12">
                                <b-table 
                                    responsive
                                    :filter="filters.payments.criteria"
                                    :fields="tables.payments.fields"
                                    :items.sync="tables.payments.items"
                                    :current-page="paginations.payments.currentPage"
                                    :per-page="paginations.payments.perPage"
                                    striped hover small bordered show-empty
                                >
                                    <template slot="row_data" slot-scope="row">
                                        <b-btn :size="'sm'" variant="success" @click.stop="row.toggleDetails">
                                            <i :class="row.detailsShowing ? 'fa fa-minus-circle' : 'fa fa-plus-circle'"></i>
                                        </b-btn>
                                    </template>
                                    <template slot="row-details" slot-scope="row">
                                        <b-card>
                                            <h5 slot="header">
                                                Payment Info ({{row.item.transaction_no}})
                                            </h5>
                                            <b-row>
                                                <b-col lg="4">
                                                    <p>Reference No : {{row.item.reference_no}}</p>
                                                    <p>Transaction Date : {{moment(row.item.payment_date, 'MMMM DD, YYYY')}}</p>
                                                    <p>Tenant : {{row.item.trade_name}}</p>
                                                    <p>Tenant Code : {{row.item.tenant_code}}</p>
                                                    <p>Contact Person : {{row.item.contact_person}}</p>
                                                    <p>Space Code : {{row.item.space_code}}</p>
                                                </b-col>
                                                <b-col lg="4">
                                                    <p>Payment Type : {{row.item.payment_type == 0 ? 'Cash' : row.item.payment_type == 1 ? 'Check' : 'Online'}}</p>
                                                    <p>Discount : {{formatNumber(row.item.discount)}}</p>
                                                    <p>Balance Paid : {{formatNumber(row.item.balance_paid)}}</p>
                                                    <p>Amount Paid : {{formatNumber(row.item.amount_paid)}}</p>
                                                </b-col>
                                                <b-col lg="4" v-if="row.item.payment_type == 1">
                                                    <p>Check Type : {{row.item.check_type_desc}}</p>
                                                    <p>Check No : {{row.item.check_no}}</p>
                                                    <p>Check Date : {{moment(row.item.check_date, 'MMMM DD, YYYY')}}</p>
                                                </b-col>
                                            </b-row>
                                        </b-card>
                                    </template>
                                    <template slot="action" slot-scope="data">
                                        <b-btn :size="'sm'" variant="success" @click="printAckReceipt(data.item.payment_id)">
                                            <i class="fa fa-print"></i>
                                        </b-btn>
                                        <b-btn :size="'sm'" variant="danger" @click="setDelete(data)">
                                            <i class="fa fa-trash"></i>
                                        </b-btn>
                                    </template>
                                    
                                </b-table>

                                <b-pagination
                                            :align="'right'"
                                            :total-rows="paginations.payments.totalRows"
                                            :per-page="paginations.payments.perPage"
                                            v-model="paginations.payments.currentPage" />
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
                                Payment Entry - {{entryMode}}
                            </span>
                        </h5>
                        <b-row>
                            <b-col sm="12">
                                <b-card>
                                    <h6 slot="header">
                                        Payment Info
                                    </h6>
                                    <b-form @keydown="resetFieldStates('payment')" autocomplete="off">
                                        <b-row>
                                            <b-col lg="4">
                                                <b-form-group>
                                                    <b-row>
                                                        <b-col lg=4>
                                                            <label class="col-form-label">Transaction No : </label>
                                                        </b-col>
                                                        <b-col lg="8">
                                                            <b-form-input
                                                                type="text"
                                                                placeholder="Auto" 
                                                                v-model="forms.payment.fields.transaction_no"
                                                                readonly>
                                                            </b-form-input>
                                                        </b-col>
                                                    </b-row>
                                                </b-form-group>
                                                <b-form-group>
                                                    <b-row>
                                                        <b-col lg=4>
                                                            <label class="col-form-label">Reference No.</label>
                                                        </b-col>
                                                        <b-col lg="8">
                                                            <b-form-input
                                                                ref="reference_no"
                                                                type="text"
                                                                placeholder="Reference No" 
                                                                v-model="forms.payment.fields.reference_no">
                                                            </b-form-input>
                                                        </b-col>
                                                    </b-row>
                                                </b-form-group>
                                                <b-form-group>
                                                    <b-row>
                                                        <b-col lg=4>
                                                            <label class="col-form-label">Trade Name : </label>
                                                        </b-col>
                                                        <b-col lg="8">
                                                            <select2
                                                                ref="tenant_id"
                                                                @input="getTenantInfo"
                                                                :allowClear="false"
                                                                :placeholder="'Select Tenants'"
                                                                v-model="forms.payment.fields.tenant_id"
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
                                                                v-model="forms.payment.fields.tenant_code"
                                                                readonly>
                                                            </b-form-input>
                                                        </b-col>
                                                    </b-row>
                                                </b-form-group>
                                            </b-col>
                                            <b-col lg="4">
                                                <b-form-group>
                                                    <b-row>
                                                        <b-col lg=4>
                                                            <label class="col-form-label">Contact Person : </label>
                                                        </b-col>
                                                        <b-col lg="8">
                                                            <b-form-input
                                                                type="text"
                                                                placeholder="Contact Person" 
                                                                v-model="forms.payment.fields.contact_person"
                                                                readonly>
                                                            </b-form-input>
                                                        </b-col>
                                                    </b-row>
                                                </b-form-group>
                                                <b-form-group>
                                                    <b-row>
                                                        <b-col lg=4>
                                                            <label class="col-form-label">Space Code : </label>
                                                        </b-col>
                                                        <b-col lg="8">
                                                            <b-form-input
                                                                type="text"
                                                                placeholder="Space Code" 
                                                                v-model="forms.payment.fields.space_code"
                                                                readonly>
                                                            </b-form-input>
                                                        </b-col>
                                                    </b-row>
                                                </b-form-group>
                                                <b-form-group>
                                                    <b-row>
                                                        <b-col lg=4>
                                                            <label class="col-form-label">Payment Type : </label>
                                                        </b-col>
                                                        <b-col lg="8">
                                                            <select2
                                                                ref="payment_type"
                                                                @input="getPaymentType"
                                                                :allowClear="false"
                                                                :placeholder="'Select Payment Type'"
                                                                v-model="forms.payment.fields.payment_type"
                                                            >
                                                                <option value="0">Cash</option>
                                                                <option value="1">Check</option>
                                                                <option value="1">Online</option>
                                                            </select2>
                                                        </b-col>
                                                    </b-row>
                                                </b-form-group>
                                                <b-form-group>
                                                    <b-row>
                                                        <b-col lg=4>
                                                            <label class="col-form-label">Payment Date : </label>
                                                        </b-col>
                                                        <b-col lg="8">
                                                            <date-picker 
                                                                v-model="forms.payment.fields.payment_date" 
                                                                lang="en" 
                                                                input-class="form-control mx-input"
                                                                format="MMMM DD, YYYY"
                                                                :clearable="false"
                                                                :disabled="true">
                                                            </date-picker>
                                                        </b-col>
                                                    </b-row>
                                                </b-form-group>
                                            </b-col>
                                            <b-col lg="4">
                                                <b-form-group>
                                                    <b-row>
                                                        <b-col lg=4>
                                                            <label class="col-form-label">Bank : </label>
                                                        </b-col>
                                                        <b-col lg="8">
                                                            <select2
                                                                ref="check_type_id"
                                                                :allowClear="false"
                                                                :placeholder="'Select Bank'"
                                                                v-model="forms.payment.fields.check_type_id"
                                                                :disabled="forms.payment.fields.payment_type == 1 ? false : true"
                                                                :reference="'checktype'"
                                                                @input="isOptionCreating"
                                                            >
                                                                <option value="-1">Create New Bank</option>
                                                                <option v-for="check in options.check_types.items" :key="check.check_type_id" :value="check.check_type_id">{{check.check_type_desc}}</option>
                                                            </select2>
                                                        </b-col>
                                                    </b-row>
                                                </b-form-group>
                                                <b-form-group>
                                                    <b-row>
                                                        <b-col lg=4>
                                                            <label class="col-form-label">Check No :</label>
                                                        </b-col>
                                                        <b-col lg="8">
                                                            <b-form-input
                                                                ref="check_no"
                                                                type="text"
                                                                placeholder="Check No." 
                                                                v-model="forms.payment.fields.check_no"
                                                                :disabled="forms.payment.fields.payment_type == 1 ? false : true">
                                                            </b-form-input>
                                                        </b-col>
                                                    </b-row>
                                                </b-form-group>
                                                <b-form-group>
                                                    <b-row>
                                                        <b-col lg=4>
                                                            <label class="col-form-label">Check Date : </label>
                                                        </b-col>
                                                        <b-col lg="8">
                                                            <date-picker 
                                                                ref="check_date"
                                                                v-model="forms.payment.fields.check_date" 
                                                                lang="en" 
                                                                input-class="form-control mx-input"
                                                                format="MMMM DD, YYYY"
                                                                :clearable="false"
                                                                :disabled="forms.payment.fields.payment_type == 1 ? false : true">
                                                            </date-picker>
                                                        </b-col>
                                                    </b-row>
                                                </b-form-group>
                                                <b-form-group>
                                                    <b-row>
                                                        <b-col lg=4>
                                                            <label class="col-form-label">Amount :</label>
                                                        </b-col>
                                                        <b-col lg="8">
                                                            <vue-autonumeric 
                                                                ref="amount"
                                                                @blur.native="computePayment()"
                                                                v-model="forms.payment.fields.amount"
                                                                :class="'form-control text-right'" 
                                                                :options="{minimumValue: 0, modifyValueOnWheel: false, emptyInputBehavior: 0}">
                                                            </vue-autonumeric>
                                                        </b-col>
                                                    </b-row>
                                                </b-form-group>
                                            </b-col>
                                        </b-row>
                                    </b-form>
                                </b-card>
                            </b-col>
                        </b-row>
                        <b-row>
                            <b-col sm="12">
                                <b-card>
                                    <h6 slot="header">
                                        Payment Details
                                    </h6>
                                    <b-form-group>
                                        <b-row>
                                            <b-col lg="4">
                                                <b-row>
                                                    <b-col lg=3>
                                                        <label class="col-form-label">Amount :</label>
                                                    </b-col>
                                                    <b-col lg="8">
                                                        <b-input-group>
                                                            <vue-autonumeric 
                                                                v-model="forms.payment.fields.amount"
                                                                :class="'form-control text-right'" 
                                                                :options="{minimumValue: 0, modifyValueOnWheel: false, emptyInputBehavior: 0}" readonly>
                                                            </vue-autonumeric>
                                                            <b-input-group-append>
                                                                <b-button 
                                                                    variant="primary" 
                                                                    @click="distributePayment()">
                                                                    <i class="fa fa-share"></i>
                                                                    Distribute
                                                                </b-button>
                                                            </b-input-group-append>
                                                        </b-input-group>
                                                    </b-col>
                                                </b-row>
                                            </b-col>
                                            <b-col lg="4">
                                            </b-col>
                                            <b-col lg="4">
                                                <b-row>
                                                    <b-col lg=5>
                                                        <label class="col-form-label">Carry Over Balance :</label>
                                                    </b-col>
                                                    <b-col lg="7">
                                                        <b-input-group>
                                                            <vue-autonumeric 
                                                                v-model="forms.payment.fields.carried_advance"
                                                                :class="'form-control text-right'" 
                                                                readonly
                                                                :options="{minimumValue: 0, modifyValueOnWheel: false, emptyInputBehavior: 0}">
                                                            </vue-autonumeric>
                                                        </b-input-group>
                                                    </b-col>
                                                </b-row>
                                            </b-col>
                                        </b-row>
                                    </b-form-group>
                                    <b-table 
                                        responsive
                                        :fields="tables.payment_details.fields"
                                        :items.sync="tables.payment_details.items"
                                        striped hover small bordered show-empty
                                    >
                                        <template slot="checker" slot-scope="data">
                                            <h3 v-if="data.item.checker == 1">
                                                <i class="fa fa-times-circle" style="color: red;"></i>
                                            </h3>
                                            <h3 v-else>
                                                <i class="fa fa-check-circle" style="color: green;"></i>
                                            </h3>
                                        </template>
                                        <template slot="is_discounted" slot-scope="data">
                                            <b-form-checkbox
                                                v-model="data.item.is_discounted"
                                                @input="getDiscount(data)"
                                                value=1
                                                unchecked-value=0>
                                            </b-form-checkbox>
                                        </template>
                                        <!-- <template slot="discount" slot-scope="data">
                                            <vue-autonumeric 
                                                @input='computePayment()'
                                                v-model="data.item.discount"
                                                :class="'form-control text-right'" 
                                                :options="{minimumValue: 0, modifyValueOnWheel: false, emptyInputBehavior: 0}">
                                            </vue-autonumeric>
                                        </template> -->
                                        <template slot="amount_paid" slot-scope="data">
                                            <vue-autonumeric 
                                                @input='computePayment()'
                                                v-model="data.item.amount_paid"
                                                :class="'form-control text-right'"
                                                :disabled="data.item.is_discounted == 1 ? true : false"
                                                :options="{minimumValue: 0, modifyValueOnWheel: false, emptyInputBehavior: 0}">
                                            </vue-autonumeric>
                                        </template>
                                    </b-table>
                                    <b-table 
                                        responsive
                                        :fields="tables.payment_summary.fields"
                                        :items.sync="tables.payment_summary.items">
                                        <template slot="HEAD_outstanding_balance" slot-scope="data">
                                            {{formatNumber(total_outstanding_balance)}}
                                        </template>
                                        <template slot="HEAD_discount" slot-scope="data">
                                            {{formatNumber(forms.payment.fields.discount)}}
                                        </template>
                                        <template slot="HEAD_amount_paid" slot-scope="data">
                                            {{formatNumber(forms.payment.fields.balance_paid)}}
                                        </template>
                                        <template slot="HEAD_remaining_balance" slot-scope="data">
                                            {{formatNumber(total_remaining_balance)}}
                                        </template>
                                    </b-table>
                                    <b-row>
                                        <b-col lg="8">
                                            <b-form-group>
                                                <label>Remarks</label>
                                                <b-form-textarea
                                                    id="remarks"
                                                    v-model="forms.payment.fields.remarks"
                                                    type="text"
                                                    :rows="7"
                                                    placeholder="Remarks">
                                                </b-form-textarea>
                                            </b-form-group>
                                        </b-col>
                                        <b-col lg="4">
                                            <!-- <b-form-group>
                                                <b-row>
                                                    <b-col lg="4">
                                                        <label class="col-form-label">Balance Paid : </label>
                                                    </b-col>
                                                    <b-col lg="8">
                                                        <vue-autonumeric 
                                                            v-model="forms.payment.fields.balance_paid"
                                                            :class="'form-control text-right'" 
                                                            :options="{minimumValue: 0, modifyValueOnWheel: false, emptyInputBehavior: 0}"
                                                            readonly>
                                                        </vue-autonumeric>
                                                    </b-col>
                                                </b-row>
                                            </b-form-group>
                                            <b-form-group>
                                                <b-row>
                                                    <b-col lg="4">
                                                        <label class="col-form-label">Total Discount : </label>
                                                    </b-col>
                                                    <b-col lg="8">
                                                        <vue-autonumeric 
                                                            v-model="forms.payment.fields.discount"
                                                            :class="'form-control text-right'" 
                                                            :options="{minimumValue: 0, modifyValueOnWheel: false, emptyInputBehavior: 0}" readonly>
                                                        </vue-autonumeric>
                                                    </b-col>
                                                </b-row>
                                            </b-form-group> -->
                                            <b-form-group>
                                                <b-row>
                                                    <b-col lg="4">
                                                        <label class="col-form-label">Advance : </label>
                                                    </b-col>
                                                    <b-col lg="8">
                                                        <vue-autonumeric 
                                                            v-model="forms.payment.fields.advance"
                                                            :class="'form-control text-right'" 
                                                            :options="{minimumValue: 0, modifyValueOnWheel: false, emptyInputBehavior: 0}" 
                                                            readonly>
                                                        </vue-autonumeric>
                                                    </b-col>
                                                </b-row>
                                            </b-form-group>
                                            <b-form-group>
                                                <b-row>
                                                    <b-col lg="4">
                                                        <label class="col-form-label">Total Paid : </label>
                                                    </b-col>
                                                    <b-col lg="8">
                                                        <vue-autonumeric 
                                                            v-model="forms.payment.fields.amount"
                                                            :class="'form-control text-right'" 
                                                            :options="{minimumValue: 0, modifyValueOnWheel: false, emptyInputBehavior: 0}" 
                                                            readonly>
                                                        </vue-autonumeric>
                                                    </b-col>
                                                </b-row>
                                            </b-form-group>
                                        </b-col>
                                    </b-row>
                                </b-card>
                            </b-col>
                        </b-row>
                        <!-- <b-row v-if="is_check" class="pull-right mt-2">
                            <b-col sm="12">
                                Check all data if correct, proceed?
                                <b-button 
                                    :disabled="forms.payment.isSaving" 
                                    variant="success" 
                                    @click="onPaymentEntry(), is_check=false">
                                    Yes
                                </b-button>
                                <b-button variant="danger" @click="is_check=false">No</b-button>
                            </b-col>
                        </b-row> -->
                        <b-row class="pull-right mt-2">
                            <b-col sm="12">
                                <input type="checkbox" v-model="is_print"> Print Acknowledgement Receipt?
                                <b-button 
                                    :disabled="forms.payment.isSaving" 
                                    variant="primary" 
                                    @click="showModalConfirmation = true">
                                    <icon v-if="forms.payment.isSaving" name="sync" spin></icon>
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
                Cancel Payment
            </div>
            <b-col lg=12>
                Are you sure you want to cancel this payment?
            </b-col>
            <div slot="modal-footer">
                <b-button :disabled="forms.payment.isSaving" variant="primary" @click="onPaymentDelete()">
                    <icon v-if="forms.payment.isSaving" name="sync" spin></icon>
                    <i class="fa fa-check"></i>
                    OK
                </b-button>
                <b-button variant="secondary" @click="showModalDelete=false">Close</b-button>            
            </div>
        </b-modal>
        <b-modal 
            v-model="showModalConfirmation"
            :noCloseOnEsc="true"
            :noCloseOnBackdrop="true"
        >
            <div slot="modal-title">
                Save Payment
            </div>
            <b-col lg=12>
                Are you sure you want to save this payment?
            </b-col>
            <div slot="modal-footer">
                <b-button :disabled="forms.payment.isSaving" variant="primary" @click="onPaymentEntry()">
                    <icon v-if="forms.payment.isSaving" name="sync" spin></icon>
                    <i class="fa fa-check"></i>
                    Yes
                </b-button>
                <b-button variant="secondary" @click="showModalConfirmation=false">Cancel</b-button>            
            </div>
        </b-modal>


        <!-- check type modal -->
        <b-modal 
            v-model="showModalCheckType"
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
                <b-button :disabled="forms.check_type.isSaving" variant="primary" @click="saveOption('checktype')">
                    <icon v-if="forms.check_type.isSaving" name="sync" spin></icon>
                    <i class="fa fa-check"></i>
                    Save
                </b-button>
                <b-button variant="secondary" @click="showModalCheckType=false">Close</b-button>            
            </div>
        </b-modal> <!-- check type modal -->

    </div>
</template>

<script>
export default {
    name: 'payments',
    data () {
      return {
            entryMode: 'Add',
            showEntry: false, //if true show entry
            showModalDelete: false,
            showModalCheckType: false,
            showModalConfirmation: false,
            options: {
                tenants: {
                    items: []
                },
                check_types: {
                    items: []
                },
            },
            forms: {
                payment: {
                    isSaving: false,
                    isDeleting: false,
                    fields: {
                        payment_id: null,
                        transaction_no: null,
                        reference_no: null,
                        tenant_id: null,
                        tenant_code: null,
                        contact_person: null,
                        space_code: null,
                        payment_type: null,
                        amount: 0.00,
                        payment_date: new Date(),
                        check_type_id: 0,
                        check_no: null,
                        check_date: null,
                        remarks: null,
                        balance_paid: 0.00,
                        advance: 0.00,
                        carried_advance: 0.00,
                        discount: 0.00,
                    }
                },
                check_type : {
                    isSaving: false,
                    fields: {
                        check_type_id: 0,
                        check_type_code: null,
                        check_type_desc: null
                    }
                }

            },
            tables: {
                payments: {
                    fields:[
                        {
                            key:'row_data',
                            label: '',
                            tdClass: '',
                            thStyle: {width: '40px'}
                        },
                        {
                            key:'transaction_no',
                            label: 'Trans No',
                            tdClass: 'align-middle',
                            sortable: true
                        },
                        {
                            key:'reference_no',
                            label: 'Reference No',
                            tdClass: 'align-middle',
                            sortable: true
                        },
                        {
                            key:'trade_name',
                            label: 'Tenant',
                            tdClass: 'align-middle',
                            sortable: true
                        },
                        {
                            key:'payment_date',
                            label: 'Payment Date',
                            formatter: (value) => {
                                return this.moment(value, 'MMMM DD, YYYY')
                            },
                            tdClass: 'align-middle',
                        },
                        {
                            key:'discount',
                            label: 'Discount',
                            thClass: 'text-right',
                            tdClass: 'text-right align-middle',
                            formatter: (value) => {
                                return this.formatNumber(value)
                            }
                        },
                        {
                            key:'balance_paid',
                            label: 'Balance Paid',
                            thClass: 'text-right',
                            tdClass: 'text-right align-middle',
                            formatter: (value) => {
                                return this.formatNumber(value)
                            }
                        },
                        {
                            key:'amount_paid',
                            label: 'Amount Paid',
                            thClass: 'text-right',
                            tdClass: 'text-right align-middle',
                            formatter: (value) => {
                                return this.formatNumber(value)
                            }
                        },
                        {
                            key: 'action',
                            label: 'Action',
                            tdClass: 'text-center align-middle',
                            thClass: 'text-center',
                            thStyle: {width: '75px'}
                        }
                    ],
                    items: []
                },
                payment_details: {
                    fields:[
                        {
                            key:'billing_id',
                            thClass: 'd-none',
                            tdClass: 'd-none'
                        },
                        {
                            key: 'checker',
                            label: '',
                            thStyle: {width: '45px'},
                            tdClass: 'text-center align-middle',
                        },
                        {
                            key:'billing_no',
                            label: 'Billing No.',
                            tdClass: 'align-middle',
                            thStyle: {width: '10%'}
                        },
                        {
                            key:'app_year',
                            label: 'App Year',
                            tdClass: 'align-middle',
                            thStyle: {width: '60px'}
                        },
                        {
                            key:'month_name',
                            label: 'Month',
                            tdClass: 'align-middle',
                            thStyle: {width: '10%'}
                        },
                        {
                            key:'outstanding_balance',
                            label: 'Outstanding Balance',
                            thClass: 'text-right',
                            tdClass: 'text-right align-middle',
                            thStyle: {width: '15%'},
                            formatter: (value) => {
                                return this.formatNumber(value, 3)
                            }
                        },
                        {
                            key: 'is_discounted',
                            label: 'is discounted?',
                            thStyle: {width: '70px'},
                            tdClass: 'text-center align-middle',
                        },
                        {
                            key:'discount',
                            label: 'Discount',
                            thClass: 'text-right',
                            tdClass: 'text-right align-middle',
                            thStyle: {width: '13%'},
                            formatter: (value) => {
                                return this.formatNumber(value, 3)
                            }
                        },
                        {
                            key:'amount_paid',
                            label: 'Amount Paid',
                            thClass: 'text-right',
                            thStyle: {width: '15%'},
                        },
                         {
                            key:'wtax_amount',
                            label: 'wtax',
                            thClass: 'text-right d-none',
                            tdClass: 'd-none',
                            thStyle: {width: '15%'},
                            formatter: (value, key, item) => {
                                if(item.is_discounted == 1){
                                    item.wtax_amount = item.dwtax_amount
                                }
                                else{
                                    item.wtax_amount = Number(item.bwtax_amount) * (Number(item.amount_paid) / (Number(item.total_amount_due) - Number(item.discount)))
                                }
                            }
                        },
                        {
                            key:'remaining_balance',
                            label: 'Remaining Balance',
                            thClass: 'text-right',
                            tdClass: 'text-right align-middle',
                            thStyle: {width: '17%'},
                            formatter: (value, key, item) => {
                                return this.formatNumber(Math.max(0, Number(item.outstanding_balance) - (Number(item.discount) + Number(item.amount_paid))));
                            }
                        },
                    ],
                    items: []
                },
                payment_summary: {
                    fields:[
                        {
                            key: 'a',
                            label: '',
                            thStyle: {width: '45px'},
                            tdClass: 'text-center align-middle',
                        },
                        {
                            key:'b',
                            label: 'Total',
                            tdClass: 'align-middle',
                            thStyle: {width: '10%'}
                        },
                        {
                            key:'c',
                            label: '',
                            tdClass: 'align-middle',
                            thStyle: {width: '60px'}
                        },
                        {
                            key:'d',
                            label: '',
                            tdClass: 'align-middle',
                            thStyle: {width: '8%'}
                        },
                        {
                            key: 'e',
                            label: '',
                            thClass: 'text-right',
                            tdClass: 'text-right align-middle',
                            thStyle: {width: '10%'}
                        },
                        {
                            key:'outstanding_balance',
                            label: '',
                            thClass: 'text-right',
                            tdClass: 'text-right align-middle',
                            thStyle: {width: '15%'}
                        },
                        {
                            key: 'f',
                            label: '',
                            thClass: 'text-right',
                            tdClass: 'text-right align-middle',
                            thStyle: {width: '10%'}
                        },
                        {
                            key:'discount',
                            label: '',
                            thClass: 'text-right',
                            thStyle: {width: '15%'},
                        },
                        {
                            key:'amount_paid',
                            label: '',
                            thClass: 'text-right',
                            thStyle: {width: '15%'},
                        },
                        {
                            key:'remaining_balance',
                            label: '',
                            thClass: 'text-right',
                            tdClass: 'text-right align-middle',
                            thStyle: {width: '15%'}
                        },
                    ],
                    items: []
                }
            },
            filters: {
                payments: {
                    criteria: null
                }
            },
            paginations: {
                payments: {
                    totalRows: 0,
                    currentPage: 1,
                    perPage: 10
                }
            },
            is_print: 0,
            print_payment_id: 0,
            total_outstanding_balance: 0,
            total_remaining_balance: 0,
            payment_id: null,
            is_check: false,
            date_from: moment().startOf('month').format('YYYY-MM-DD'),
            date_to: moment().endOf('month').format('YYYY-MM-DD'),
            row: []
        }
    },
    methods:{
        validateForm(){
            var totalAmountPaid = 0
            this.tables.payment_details.items.forEach(billing => {
                billing.checker = 0
                
                if(Number(billing.outstanding_balance).toFixed(2) < (Number(billing.discount).toFixed(2) + Number(billing.amount_paid).toFixed(2))){
                    billing.checker = 1
                    this.$notify({
                        type: 'error',
                        group: 'notification',
                        title: 'Error',
                        text: "The payment is greater to the balance."
                    })
                    return false
                }
                totalAmountPaid += billing.amount_paid
            })

            var total = Number(this.forms.payment.fields.amount) - 
            (Number(totalAmountPaid) - Number(this.forms.payment.fields.carried_advance) + Number(this.forms.payment.fields.advance))
            if(total >= 0){
                this.showModalConfirmation = false
                return true
            }
            else{
                if(total >= -0.09){
                    this.showModalConfirmation = false
                    return true
                }
                else{
                    this.$notify({
                        type: 'error',
                        group: 'notification',
                        title: 'Error',
                        text: "The sum of amount paid is not equal to the amount given."
                    })
                    this.showModalConfirmation = false
                    return false
                }
            }
        },
        async onPaymentEntry(){
            this.forms.payment.fields.payment_details = this.tables.payment_details.items

            if(this.validateForm()){
                if(this.entryMode == 'Add'){
                    this.forms.payment.isSaving = true
                    this.resetFieldStates('payment')
                    await this.$http.post('api/payment', this.forms.payment.fields,{
                        headers: {
                                Authorization: 'Bearer ' + localStorage.getItem('token')
                            }
                    })
                    .then((response) => {  
                        this.forms.payment.isSaving = false
                        this.clearFields('payment')
                        this.$notify({
                            type: 'success',
                            group: 'notification',
                            title: 'Success!',
                            text: 'The record has been successfully created.'
                        })

                        this.tables.payments.items.unshift(response.data.data)
                        this.print_payment_id = response.data.data.payment_id
                        this.paginations.payments.totalRows++

                        this.showEntry = false
                        this.showModalConfirmation = false
                    }).catch(error => {
                        this.forms.payment.isSaving = false
                        this.showModalConfirmation = false
                        if (!error.response) return
                            const errors = error.response.data.errors
                        var a = 0
                        for (var key in errors) {
                            if(a == 0){
                                this.focusElement(key)
                                this.$notify({
                                    type: 'error',
                                    group: 'notification',
                                    title: 'Error!',
                                    text: errors[key][0]
                                })
                            }
                            
                            a++
                        }
                    })
                    if(this.is_print){
                        await this.printAckReceipt(this.print_payment_id)
                    }
                }
                else{
                    this.updateEntity('payment', 'payment_id', false, this.row)
                }
            }
        },
        onPaymentDelete(){
            this.deleteEntity('payment', this.payment_id, true, 'payments', 'payment_id')
        },
        async setDelete(data){
            if(await this.checkIfUsed('payment', data.item.payment_id) == true){
                this.$notify({
                    type: 'error',
                    group: 'notification',
                    title: 'Error!',
                    text: "Unable to delete, this record is being used by other transactions."
                })
                return
            }
            this.payment_id = data.item.payment_id
            this.showModalDelete = true
        },
        setUpdate(data){
            this.row = data.item
            this.resetFieldStates('payment')
            this.fillEntityForm('payment', data.item.payment_id)
            this.showEntry=true
            this.entryMode='Edit'
        },
        getTenantInfo: async function (value, data) {
            this.forms.payment.fields.amount = 0
            if(data.length > 0){
                var tenant = this.options.tenants.items[data[0].element.index]
                this.forms.payment.fields.tenant_code = tenant.tenant_code
                this.forms.payment.fields.contact_person = tenant.contact_person
                this.forms.payment.fields.space_code = tenant.space_code
            }

            await this.$http.get('api/billing/balance/' + tenant.tenant_id,{
                    headers: {
                        Authorization: 'Bearer ' + localStorage.getItem('token')
                    }
                })
                .then((response) => {
                    const res = response.data
                    this.tables.payment_details.items = res.data
                })
                .catch(error => {
                    if (!error.response) 
                    return console.log(error)
                })
            
            await this.$http.get('api/payment/advance/' + tenant.tenant_id,{
                headers:{
                    Authorization: 'Bearer ' + localStorage.getItem('token')
                }
            }).then((response) => {
                const res = response.data
                this.forms.payment.fields.carried_advance = 0
                if(res.data.length > 0){
                    this.forms.payment.fields.carried_advance = res.data[0].advance
                }
            }).catch(error => {
                if (!error.response) 
                return console.log(error)
            })

            this.distributePayment()
        },
        getPaymentType: function (value, data) {
            if(value == 0){
                this.forms.payment.fields.check_type = null
                this.forms.payment.fields.check_no = null
                this.forms.payment.fields.check_date = null
            }
        },
        getDiscount(data){
            if(data.item.is_discounted == 1){
                data.item.discount = data.item.bill_discount
                data.item.amount_paid = Number(data.item.outstanding_balance) - Number(data.item.discount)
            }
            else{
                data.item.discount = 0.00
                data.item.amount_paid = 0.00
            }

            this.computePayment()
        },
        distributePayment(){
            var amount = Number(this.forms.payment.fields.amount) + Number(this.forms.payment.fields.carried_advance)
            var balance_paid = 0
            var total_outstanding_balance = 0
            var discount = 0
            this.tables.payment_details.items.forEach(billing => {
                discount += Number(billing.discount)
                amount += Number(billing.discount)
                total_outstanding_balance += Number(billing.outstanding_balance)
                if(amount > Number(billing.outstanding_balance)){
                    billing.amount_paid = Math.max(0, Number(billing.outstanding_balance) - Number(billing.discount))
                    balance_paid += Math.max(0, Number(billing.outstanding_balance) - Number(billing.discount))
                    amount -= Number(billing.outstanding_balance)
                }
                else{
                    billing.amount_paid = Math.max(0, Number(amount) - Number(billing.discount))
                    balance_paid += Math.max(0, Number(amount) - Number(billing.discount))
                    amount = 0
                }
            })
            this.forms.payment.fields.advance = Math.max(0, (Number(this.forms.payment.fields.amount) + Number(this.forms.payment.fields.carried_advance)) - Number(balance_paid)).toFixed(2)
            this.forms.payment.fields.balance_paid = balance_paid
            this.total_outstanding_balance = total_outstanding_balance
        },
        computePayment(){
            var balance = 0
            var totalDiscount = 0
            var totalAmount = 0
            var remaining_balance = 0

            this.tables.payment_details.items.forEach(billing => {
                balance += Math.max(0, Number(billing.outstanding_balance) - Number(billing.discount))
                totalDiscount += Number(billing.discount)
                totalAmount += Number(billing.amount_paid)
                remaining_balance += Math.max(0, Number(billing.outstanding_balance) - (Number(billing.discount) + Number(billing.amount_paid)))
            })

            this.forms.payment.fields.advance = Math.max(0, (Number(this.forms.payment.fields.amount) + Number(this.forms.payment.fields.carried_advance)) - Number(balance)).toFixed(2)
            this.forms.payment.fields.discount = totalDiscount
            this.forms.payment.fields.balance_paid = totalAmount
            this.total_remaining_balance = remaining_balance
        },
        isOptionCreating: function(value, data, reference){
            if(value == -1){
                if(reference == 'checktype'){
                    //show department entry modal
                    this.showModalCheckType = true
                    this.forms.payment.fields.check_type_id = "null"
                }
            }
        },
        saveOption(reference){
            if(reference == 'checktype'){
                this.createOptionsEntity('check_type', 'showModalCheckType', 'check_types', 'payment','check_type_id')
            }
        },
        filterPayments(){
            var from = this.moment(this.date_from, 'YYYY-MM-DD')
            var to = this.moment(this.date_to, 'YYYY-MM-DD')
            this.filterTableList('payments', from, to)
        },
        printAckReceipt(payment_id){
            let routeData = this.$router.resolve({name: 'Acknowledgement Receipt', query: {payment_id: payment_id}});
            window.open(routeData.href, '_blank');
            //this.$router.push({ name: 'soa' })
        },
    },
    created () {
        this.filterTableList('payments', this.date_from, this.date_to)
        this.fillOptionsList('tenants')
        this.fillOptionsList('check_types')
    },
    watch: {
        showEntry: function (showEntry) {
            if(showEntry){
                let self = this
                Vue.nextTick(function(){
                    self.focusElement('reference_no')
                })
            }
        },
    }
  }
</script>

