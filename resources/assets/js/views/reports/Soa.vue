<style>
</style>

<template>
    <!--<b-animated fade-in>  main container -->
    <div hidden>
        <div class="animated fadeIn" ref="soa">
            <b-row>
                <b-col sm="12">
                    <b-card >
                        <table width="100%">
                            <tr>
                                <td width="80%" class="align-center">
                                    <div style="font-size: 20pt;font-weight: 500;">{{company_info.company_name}}</div>
                                    <div style="font-size: 11pt;font-weight: 500;margin-top: 0px;">{{company_info.company_address}}</div>
                                    <div style="font-size: 11pt;font-weight: 500;margin-top: 0px;">{{company_info.email_address}}</div>
                                    <div style="font-size: 11pt;font-weight: 500;margin-top: 0px;">{{company_info.landline}}/{{company_info.mobile_number}}</div>
                                </td>
                                <td width="20%" style="object-fit: cover;"><img :src="company_info.logo" style="height: 90px; width: 200px; text-align: right;"></td>
                            </tr>
                        </table>
                        <div style="font-size: 11pt; width: 100%;">
                            <!-- <div style="font-size: 20pt;font-weight: 500;">{{company_info.company_name}}</div>
                            <div style="font-size: 11pt;font-weight: 500;margin-top: 0px;">{{company_info.company_address}}</div>
                            <img :src="company_info.logo" width="200px" height="150px"/>
                            <br><br> -->
                            <div style="font-size: 16pt; font-weight: 500; text-align: right">Statement of Account</div>
                        </div>
                        <table style="width: 100%">
                            <thead>
                                <tr>
                                    <th colspan="4" style="text-align: left; font-size: 10pt;">ACCOUNT DETAILS</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style="width:12%;">TENANT :</td>
                                    <td style="width:38%; text-align: left;">{{ billing.trade_name }}</td>
                                    <td style="width:12%;">BILLING NO :</td>
                                    <td style="width:38%; text-align: left;">{{ billing.billing_no }}</td>
                                </tr>
                                <tr>
                                    <td>NAME :</td>
                                    <td>{{ billing.contract_signatory }}</td>
                                    <td>BILLING PERIOD :</td>
                                    <td>{{ moment(billing.period_start_date, "MMMM DD, YYYY") }} to {{ moment(billing.period_end_date, "MMMM DD, YYYY") }}</td>
                                </tr>
                                <tr>
                                    <td>TENANT CODE :</td>
                                    <td>{{ billing.tenant_code }}</td>
                                    <td>SPACE CODE :</td>
                                    <td>{{ billing.space_code }}</td>
                                </tr>
                                <tr>
                                    <td>CATEGORY :</td>
                                    <td>{{ billing.category_desc }}</td>
                                    <td>LEASED AREA :</td>
                                    <td>{{ formatNumber(billing.contract_floor_area) }}sqm</td>
                                </tr>
                                <tr>
                                    <td>LOCATION :</td>
                                    <td>{{ billing.location_desc }}</td>
                                    <td>DUE DATE :</td>
                                    <td>{{ moment(billing.period_due_date, "MMMM DD, YYYY") }}</td>
                                </tr>
                            </tbody>
                        </table>
                        <table style="width: 100%">
                            <thead>
                                <tr>
                                    <th colspan="4" class="asdf" style="text-align: left; font-size: 10pt;">PREVIOUS BALANCE</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="2">BALANCE AS OF : {{ billing.prev_month }}</td>
                                    <td style="width: 15%"></td>
                                    <td style="text-align: right; width: 15%">{{ formatNumber(as_of_balance) }}</td>
                                </tr>
                                <tr v-if="payments.length > 0">
                                    <td colspan="2">PAYMENTS :</td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <!-- may foreach dito -->
                                <tr v-if="payments.length > 0" v-for="payment in payments">
                                    <td style="width: 8%"></td>
                                    <td>{{ moment(payment.payment_date, 'MMMM DD, Y') }} Trans. No.: {{ payment.transaction_no }} Ref. No. {{ payment.reference_no }} - {{payment.trans_type}}<span v-if="payment.payment_type==1"><br>{{payment.check_type_desc}} Check No - {{payment.check_no}} Date - {{moment(payment.check_date, 'MMMM DD, Y')}}</span></td>
                                    <td></td>
                                    <td style="text-align:right;">({{formatNumber(payment.payment)}})<span v-if="payment.payment_type==1"><br>&nbsp;</span></td>
                                </tr>
                                <tr>
                                    <td colspan="2">BALANCE : </td>
                                    <td style="width: 15%"></td>
                                    <td style="text-align: right; width: 15%">{{ formatNumber(previous_balance) }}</td>
                                </tr>
                                <tr v-if="billing.interest_total > 0 || billing.penalty_total > 0">
                                    <td colspan="2">Additional Charges :</td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <!-- may foreach dito -->
                                <tr v-if="billing.interest_total > 0">
                                    <td style="width: 8%"></td>
                                    <td>Interest Charges ({{formatNumber(billing.interested_amount)}} * {{formatNumber(billing.interest_percent)}})</td>
                                    <td></td>
                                    <td style="text-align:right;">{{formatNumber(billing.interest_total)}}</td>
                                </tr>
                                <tr v-if="billing.penalty_total > 0">
                                    <td style="width: 8%"></td>
                                    <td>Penalty Charges ({{formatNumber(billing.penaltied_amount)}} * {{formatNumber(billing.penalty_percent)}})</td>
                                    <td></td>
                                    <td style="text-align:right;">{{formatNumber(billing.penalty_total)}}</td>
                                </tr>
                                <tr>
                                    <td colspan="3" style="font-size: 9pt;"><b>TOTAL OUTSTANDING BALANCE</b></td>
                                    <td style="text-align: right; font-size: 9pt;"><b>{{ formatNumber(total_outstanding_balance) }}</b></td>
                                </tr>
                            </tbody>
                        </table>
                        <table style="width: 100%; margin-top: 5px;">
                            <thead>
                                <tr>
                                    <th colspan="6" style="text-align: left; font-size: 10pt;">CURRENT BILLING</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style="width: 8%"></td>
                                    <td colspan="3"></td>
                                    <td style="text-align: right; width: 15%">BASIC RENT</td>
                                    <td style="text-align: right; width: 15%">DISCOUNTED RENT</td>
                                </tr>
                                <tr>
                                    <td colspan="6">RENTAL :</td>
                                </tr>
                                <tr v-for="schedule in schedules">
                                    <td></td>
                                    <td colspan="3">{{schedule.month_name}} <i>{{ schedule.billing_schedule_notes }}</i></td>
                                    <td style="text-align: right">{{formatNumber(schedule.line_total)}}</td>
                                    <td style="text-align: right">{{formatNumber(billing.contract_discounted_rent)}}</td>
                                </tr>
                                <tr>
                                    <td colspan="2"></td>
                                    <td style="text-align: right">RATE</td>
                                    <td style="text-align: right">READING</td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr v-if="utilities.length > 0">
                                    <td colspan="2">UTILITIES :</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <!-- may foreach dito -->
                                <tr v-if="utilities.length > 0" v-for="utility in utilities">
                                    <td></td>
                                    <td>{{ utility.charge_desc }} <i>{{ utility.contract_notes }}</i> </td>
                                    <td style="text-align: right">{{ formatNumber(utility.contract_rate) }}</td>
                                    <td style="text-align: right">{{ formatNumber(utility.contract_default_reading) }}</td>
                                    <td style="text-align: right">{{ formatNumber(utility.billing_util_line_total) }}</td>
                                    <td style="text-align: right">{{ formatNumber(utility.billing_util_line_total) }}</td>
                                </tr>
                                <tr v-if="miscellaneous.length > 0">
                                    <td colspan="2">MISCELLANEOUS CHARGES :</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <!-- may foreach dito -->
                                <tr v-if="miscellaneous.length > 0" v-for="misc in miscellaneous">
                                    <td></td>
                                    <td>{{ misc.charge_desc }} <i>{{ misc.contract_notes }}</i></td>
                                    <td style="text-align: right">{{ formatNumber(misc.contract_rate) }}</td>
                                    <td style="text-align: right">{{ formatNumber(misc.contract_default_reading) }}</td>
                                    <td style="text-align: right">{{ formatNumber(misc.billing_misc_line_total) }}</td>
                                    <td style="text-align: right">{{ formatNumber(misc.billing_misc_line_total) }}</td>
                                </tr>
                                <tr v-if="other.lenght > 0">
                                    <td colspan="2">OTHER CHARGES :</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <!-- may foreach dito -->
                                <tr v-if="other.lenght > 0" v-for="othr in other">
                                    <td></td>
                                    <td>{{ othr.charge_desc }} <i>{{ othr.contract_notes }}</i></td>
                                    <td style="text-align: right">{{ formatNumber(othr.contract_rate) }}</td>
                                    <td style="text-align: right">{{ formatNumber(othr.contract_default_reading) }}</td>
                                    <td style="text-align: right">{{ formatNumber(othr.billing_othr_line_total) }}</td>
                                    <td style="text-align: right">{{ formatNumber(othr.billing_othr_line_total) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="2">TAXES :</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>WITHHOLDING TAX</td>
                                    <td style="text-align: right">-{{ formatNumber(billing.wtax_percent) }}%</td>
                                    <td></td>
                                    <td style="text-align: right">({{ formatNumber(billing.wtax_amount) }})</td>
                                    <td style="text-align: right">({{ formatNumber(billing.total_discounted_rent * (billing.wtax_percent / 100)) }})</td>
                                </tr>
                                 <tr>
                                    <td></td>
                                    <td>VAT</td>
                                    <td style="text-align: right">{{ formatNumber(billing.vat_percent) }}%</td>
                                    <td></td>
                                    <td style="text-align: right">{{ formatNumber(billing.total_vat) }}</td>
                                    <td style="text-align: right">{{ formatNumber(billing.discounted_vatable_amount * (billing.vat_percent / 100)) }}</td>
                                </tr>
                                <tr v-if="adjustments.length > 0">
                                    <td colspan="4">ADJUSTMENTS :</td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <!-- may foreach dito -->
                                <tr v-if="adjustments.length > 0" v-for="adjustment in adjustments">
                                    <td></td>
                                    <td>Adj. No.: {{ adjustment.adjustment_no }} - {{adjustment.adjustment_type}}</td>
                                    <td colspan="2"></td>
                                    <td v-if="adjustment.adjustment_type == 'IN'" style="text-align:right;">{{ formatNumber(adjustment.amount) }}</td>
                                    <td v-else style="text-align:right;">({{ formatNumber(adjustment.amount) }})</td>
                                     <td v-if="adjustment.adjustment_type == 'IN'" style="text-align:right;">{{ formatNumber(adjustment.amount) }}</td>
                                    <td v-else style="text-align:right;">({{ formatNumber(adjustment.amount) }})</td>
                                </tr>
                                <tr>
                                    <td colspan="4" style="font-size: 9pt;"><b>TOTAL CURRENT CHARGES</b></td>
                                    <td style="text-align: right; border-top: 1px solid gray"><b>{{ formatNumber(total_fixed_charges) }}</b></td>
                                    <td style="text-align: right; border-top: 1px solid gray"><b>{{ formatNumber(total_discounted_charges) }}</b></td>
                                </tr>
                            </tbody>
                        </table>
                        <table class="total" style="width: 100%; margin-top: 5px;">
                            <thead>
                                <tr>
                                    <th style="text-align: left; width: 70%"><h2>TOTAL AMOUNT DUE</h2></th>
                                    <th style="text-align: right; width: 15%;"><h2>{{ formatNumber(this.total_fixed_amount_due) }}</h2></th>
                                    <th style="text-align: right; width:15%;"><h2>{{ formatNumber(this.total_discounted_amounted_due) }}</h2></th>
                                </tr>
                            </thead>
                        </table>
                        <div style="width: 100%">
                            * NOTE
                            <ol>
                                <li v-for="note in notes">
                                    {{note.notes}}
                                </li>
                            </ol> 
                        </div>
                        <table style="width: 100%">
                            <tbody>
                                <tr style="text-align: center">
                                    <td>Prepared By:</td>
                                    <td>Checked By:</td>
                                    <td>Mall Administration:</td>
                                    <td>Received Statement:</td>
                                </tr>
                                <tr>
                                    <td></td>
                                </tr>
                                <tr style="text-align: center">
                                    <td>_________________________</td>
                                    <td>_________________________</td>
                                    <td>_________________________</td>
                                    <td>_________________________</td>
                                </tr>
                            </tbody>
                        </table>
                    </b-card>
                </b-col>
            </b-row>
        </div>
    </div>
</template>
<script>
export default {
    name: 'soa',
    data () {
        return {
            billing_id: null,
            penalty: {
                contract_rate: null,
                billing_othr_line_total: null,
            },
            interest: {
                contract_rate: null,
                billing_othr_line_total: null,
            },
            billing: [],
            payments: [],
            adjustments: [],
            schedules: [],
            utilities: [],
            miscellaneous: [],
            other: [],
            total_adjustment_in: 0,
            total_adjustment_out: 0,
            total_fixed_charges: 0,
            total_discounted_charges: 0,
            total_fixed_amount_due: 0,
            total_discounted_amounted_due: 0,
            total_outstanding_balance: 0,
            previous_balance: null,
            as_of_balance: null,
            company_info: [],
            notes: [],
            options: {
                months: {
                    items: []
                },
            },
            cssText: `
                body{
                    font-family: Calibri;
                    font-size: 8pt!important;
                }
                table {
                    font-size: 8pt!important;
                }
                tr td:first-child {
                    padding-left: 10px;
                }
                th {
                    background: lightgray;
                }
                .total {
                    background: lightgray;
                }
                .border-left {
                    border-left: 1px solid gray;
                }
                @page {
                    margin: 0.4in;
                }
                `
        }
    },
    async created(){
        this.billing_id = this.$route.query.billing_id

        await this.$http.get('/api/companysetting/1', {
            headers: {
                        Authorization: 'Bearer ' + localStorage.getItem('token')
                    }
        })
        .then((response) => {
            const res = response.data
            this.company_info = res.data
        })
        .catch(error => {
            if (!error.response) return
            console.log(error)
        })

        await this.$http.get('/api/companysettingnotes', {
            headers: {
                        Authorization: 'Bearer ' + localStorage.getItem('token')
                    }
        })
        .then((response) => {
            const res = response.data
            this.notes = res.data
        })
        .catch(error => {
            if (!error.response) return
            console.log(error)
        })

        await this.$http.get('api/billing/' + this.billing_id +'/' + true, {
                headers: {
                        Authorization: 'Bearer ' + localStorage.getItem('token')
                    }
            })
            .then((response) => {
                const res = response.data.data
                this.billing = res
                var month_id = 0
                if(this.billing.month_id == 1){
                    month_id = 13
                }
                var pmonth = new Date(this.billing.app_year + '-' + Number(month_id - 1) +  '-1')
                this.billing.prev_month = this.moment(pmonth, 'MMMM').toUpperCase()
            })
            .catch(error => {
              if (!error.response) return
              console.log(error)
            })

        await this.$http.get('api/billing/'+this.billing.month_id+'/'+this.billing.app_year+'/'+this.billing.tenant_id,{
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

        await this.$http.get('api/payments/'+this.billing.month_id+'/'+this.billing.app_year+'/'+this.billing.tenant_id,{
                headers: {
                    Authorization: 'Bearer ' + localStorage.getItem('token')
                }
            })
            .then((response) => {
                const res = response.data
                this.payments = res.data;
            })
            .catch(error => {
                    if (!error.response) 
                    return console.log(error)
            })
        
        await this.$http.get('api/adjustments/'+this.billing.month_id+'/'+this.billing.app_year+'/'+this.billing.tenant_id,{
                headers: {
                    Authorization: 'Bearer ' + localStorage.getItem('token')
                }
            })
            .then((response) => {
                const res = response.data
                this.adjustments = res.data;
                this.adjustments.forEach(adjustment => {
                    if(adjustment.adjustment_type == "IN"){
                        this.total_adjustment_in += Number(adjustment.amount)
                    }
                    else{
                        this.total_adjustment_out += Number(adjustment.amount)
                    }
                });
            })
            .catch(error => {
                    if (!error.response) 
                    return console.log(error)
            })

        await this.$http.get('api/billing/as_of/'+this.billing.month_id+'/'+this.billing.app_year+'/'+this.billing.tenant_id,{
                headers: {
                    Authorization: 'Bearer ' + localStorage.getItem('token')
                }
            })
            .then((response) => {
                const res = response.data
                this.as_of_balance = res[0]['asOfBalance'];
            })
            .catch(error => {
                    if (!error.response) 
                    return console.log(error)
            })
        
        await this.$http.get('/api/billingSC/sc/'+ this.billing_id,{
                headers: {
                        Authorization: 'Bearer ' + localStorage.getItem('token')
                    }
            })
            .then((response) => {
                const res = response.data
                this.schedules = res.schedules
                this.utilities = res.util_charges
                this.miscellaneous = res.misc_charges
                this.other = res.othr_charges
            })
            .catch(error => {
              if (!error.response) return
              console.log(error)
            })

        await this.computeAll()
        await this.d.print(this.$refs.soa, this.cssText)
    },
    mounted(){
        const { Printd } = window.printd
        this.d = new Printd()

        const { contentWindow } = this.d.getIFrame()
        contentWindow.addEventListener(
            'afterprint', () => window.close()
        )
    },
    methods: {
        computeAll(){
            // total current charges 
            //fixed rental
            this.total_fixed_charges = Number(this.billing.total_amount_due)
            //discounted rental
            this.total_discounted_charges = Number(this.billing.discounted_total_amount_due)

            //total amount due
            //fixed rental
            this.total_outstanding_balance = Number(this.previous_balance) + Number(this.billing.interest_total) + Number(this.billing.penalty_total)
            this.total_fixed_amount_due = Number(this.previous_balance) + Number(this.total_fixed_charges)
            //discounted rental
            this.total_discounted_amounted_due = Number(this.previous_balance) + Number(this.total_discounted_charges)
        }
    },
  }
</script>

