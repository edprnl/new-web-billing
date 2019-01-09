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
                            <b-col  sm="4">
                                    <b-button variant="primary" @click="resetFieldStates('payment'),clearFields('payment'),entryMode = 'Add', showEntry = true, tables.payment_details.items = []">
                                            <i class="fa fa-plus-circle"></i> Create New Payment
                                    </b-button>
                            </b-col>

                            <b-col  sm="4">
                                <span></span>
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
                                    :filter="filters.payments.criteria"
                                    :fields="tables.payments.fields"
                                    :items.sync="tables.payments.items"
                                    :current-page="paginations.payments.currentPage"
                                    :per-page="paginations.payments.perPage"
                                    striped hover small bordered show-empty
                                >
                                    <template slot="action" slot-scope="data">
                                        <b-btn :size="'sm'" variant="danger" @click="setDelete(data)">
                                            <i class="fa fa-trash"></i>
                                        </b-btn>
                                    </template>
                                    
                                </b-table>

                                <b-pagination
                                            :align="'right'"
                                            :total-rows="paginations.payments.totalRows"
                                            :per-page="paginations.payments.perPage"
                                            v-model="paginations.payments.criteria" />
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
                                                            <label class="col-form-label">Check Type : </label>
                                                        </b-col>
                                                        <b-col lg="8">
                                                            <select2
                                                                :allowClear="false"
                                                                :placeholder="'Select Check Type'"
                                                                v-model="forms.payment.fields.check_type_id"
                                                                :disabled="forms.payment.fields.payment_type == 1 ? false : true"
                                                                :reference="'checktype'"
                                                                @input="isOptionCreating"
                                                            >
                                                                <option value="-1">Create New Check Type</option>
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
                                                                @blur.native="distributePayment()"
                                                                :class="'form-control text-right'" 
                                                                :options="{minimumValue: 0, modifyValueOnWheel: false, emptyInputBehavior: 0}">
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
                                                                v-model="carried_advance"
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
                                        :fields="tables.payment_details.fields"
                                        :items.sync="tables.payment_details.items"
                                        striped hover small bordered show-empty
                                    >
                                        <template slot="discount" slot-scope="data">
                                            <vue-autonumeric 
                                                @input='computeDiscount()'
                                                v-model="data.item.discount"
                                                :class="'form-control text-right'" 
                                                :options="{minimumValue: 0, modifyValueOnWheel: false, emptyInputBehavior: 0}">
                                            </vue-autonumeric>
                                        </template>
                                        <template slot="amount_paid" slot-scope="data">
                                            <vue-autonumeric 
                                                v-model="data.item.amount_paid"
                                                :class="'form-control text-right'"
                                                @blur.native="computePayment()"
                                                :options="{minimumValue: 0, modifyValueOnWheel: false, emptyInputBehavior: 0}">
                                            </vue-autonumeric>
                                        </template>
                                    </b-table>
                                    <b-row>
                                        <b-col lg="8">
                                            <b-form-group>
                                                <label>Remarks</label>
                                                <b-form-textarea
                                                    id="remarks"
                                                    v-model="forms.payment.fields.remarks"
                                                    :state="forms.payment.states.remarks"
                                                    type="text"
                                                    :rows="7"
                                                    placeholder="Remarks">
                                                </b-form-textarea>
                                            </b-form-group>
                                        </b-col>
                                        <b-col lg="4">
                                            <b-form-group>
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
                                            </b-form-group>
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
                        <b-row v-if="is_check" class="pull-right mt-2">
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
                        </b-row>
                        <b-row v-else class="pull-right mt-2">
                            <b-col sm="12">
                                <b-button 
                                    :disabled="forms.payment.isSaving" 
                                    variant="primary" 
                                    @click="is_check=true">
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
                            :state="forms.check_type.states.check_type_code"
                            type="text"
                            placeholder="Check Type Code">
                        </b-form-input>
                        <b-form-invalid-feedback>
                            <i class="fa fa-exclamation-triangle text-danger"></i>
                            <span v-for="itemError in forms.check_type.errors.check_type_code">
                                {{itemError}}
                            </span>
                        </b-form-invalid-feedback>
                    </b-form-group>
                    <b-form-group>
                        <label>* Check Type Desc</label>
                        <b-form-input
                            id="check_type_desc"
                            v-model="forms.check_type.fields.check_type_desc"
                            :state="forms.check_type.states.check_type_desc"
                            type="text"
                            placeholder="Check Type Description">
                        </b-form-input>
                        <b-form-invalid-feedback>
                            <i class="fa fa-exclamation-triangle text-danger"></i>
                            <span v-for="itemError in forms.check_type.errors.check_type_desc">
                                {{itemError}}
                            </span>
                        </b-form-invalid-feedback>
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
                        check_type_id: null,
                        check_no: null,
                        check_date: null,
                        remarks: null,
                        balance_paid: 0.00,
                        advance: 0.00,
                        discount: 0.00,
                    },
                    states: {
                        payment_id: null,
                        transaction_no: null,
                        reference_no: null,
                        tenant_id: null,
                        tenant_code: null,
                        contact_person: null,
                        space_code: null,
                        payment_type: null,
                        amount: null,
                        payment_date: null,
                        check_type_id: null,
                        check_no: null,
                        check_date: null,
                        remarks: null,
                        balance_paid: null,
                        advance: null,
                        discount: null,
                    },
                    errors: {
                        payment_id: null,
                        transaction_no: null,
                        reference_no: null,
                        tenant_id: null,
                        tenant_code: null,
                        contact_person: null,
                        space_code: null,
                        payment_type: null,
                        amount: null,
                        payment_date: null,
                        check_type_id: null,
                        check_no: null,
                        check_date: null,
                        remarks: null,
                        balance_paid: null,
                        advance: null,
                        discount: null,
                    }
                },
                check_type : {
                    isSaving: false,
                    fields: {
                        check_type_id: null,
                        check_type_code: null,
                        check_type_desc: null
                    },
                    states: {
                        check_type_code: null,
                        check_type_desc: null
                    },
                    errors: {
                        check_type_code: null,
                        check_type_desc: null
                    }
                }

            },
            tables: {
                payments: {
                    fields:[
                        {
                            key:'transaction_no',
                            label: 'Trans No',
                            tdClass: 'align-middle',
                        },
                        {
                            key:'reference_no',
                            label: 'Reference No',
                            tdClass: 'align-middle',
                        },
                        {
                            key:'trade_name',
                            label: 'Tenant',
                            tdClass: 'align-middle',
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
                            label: 'Action'
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
                            key:'billing_no',
                            label: 'Billing No.',
                            tdClass: 'align-middle'
                        },
                        {
                            key:'app_year',
                            label: 'App Year',
                            tdClass: 'align-middle'
                        },
                        {
                            key:'month_name',
                            label: 'Month',
                            tdClass: 'align-middle'
                        },
                        {
                            key:'outstanding_balance',
                            label: 'Outstanding Balance',
                            thClass: 'text-right',
                            tdClass: 'text-right align-middle',
                            formatter: (value) => {
                                return this.formatNumber(value)
                            }
                        },
                        {
                            key:'discount',
                            label: 'Discount',
                            thClass: 'text-right'
                        },
                        {
                            key:'amount_paid',
                            label: 'Amount Paid',
                            thClass: 'text-right'
                        },
                        {
                            key:'remaining_balance',
                            label: 'Remaining Balance',
                            thClass: 'text-right',
                            tdClass: 'text-right align-middle',
                            formatter: (value, key, item) => {
                                return this.formatNumber(Math.max(0, Number(item.outstanding_balance) - (Number(item.discount) + Number(item.amount_paid))));
                            }
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
            payment_id: null,
            is_check: false,
            carried_advance: 0,
            row: []
        }
    },
    methods:{
        onPaymentEntry(){
            alert()
            this.forms.payment.fields.payment_details = this.tables.payment_details.items

            if(this.entryMode == 'Add'){
                this.createEntity('payment', false, 'payments')
            }
            else{
                this.updateEntity('payment', 'payment_id', false, this.row)
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
                this.carried_advance = 0
                if(res.data.length > 0){
                    this.carried_advance = res.data[0].advance
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
        distributePayment(){
            var amount = Number(this.forms.payment.fields.amount) + Number(this.carried_advance)
            //alert(this.carried_advance)
            var balance_paid = 0
            var discount = 0
            this.tables.payment_details.items.forEach(billing => {
                discount += Number(billing.discount)
                amount += Number(billing.discount)
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
            this.forms.payment.fields.advance = Math.max(0, (Number(this.forms.payment.fields.amount) + Number(this.carried_advance)) - Number(balance_paid))
            this.forms.payment.fields.balance_paid = balance_paid
        },
        computeDiscount(){
            var totalDiscount = 0
            this.tables.payment_details.items.forEach(billing => {
                totalDiscount += Number(billing.discount)
            })
            this.forms.payment.fields.discount = totalDiscount
            this.distributePayment()
        },
        computePayment(){
            var totalPayment = 0
            this.tables.payment_details.items.forEach(billing => {
                totalPayment += Number(billing.amount_paid)
            })
            totalPayment = Math.max(0,Number(totalPayment) - Number(this.carried_advance))
            this.forms.payment.fields.amount = totalPayment
            this.distributePayment()
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
           
        }
    },
    created () {
      this.fillTableList('payments')
      this.fillOptionsList('tenants')
      this.fillOptionsList('check_types')
    },
    watch: {
        showEntry: function (showEntry) {
            if(showEntry){
                let self = this
                Vue.nextTick(function(){
                    self.focusElement('reference_no', true)
                })
            }
        },
    }
  }
</script>

