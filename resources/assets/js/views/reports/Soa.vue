<style>
</style>

<template>
    <!--<b-animated fade-in>  main container -->
    <div hidden>
        <div class="animated fadeIn" ref="soa">
            <b-row>
                <b-col sm="12">
                    <b-card >
                        <div style="font-size: 11pt; width: 100%;">
                            <div style="font-size: 20pt;font-weight: 500;">Magic Star Mall</div>
                            <div style="font-size: 11pt;font-weight: 500;margin-top: 0px;">Cut-Cut 1st, Romulo Boulevard, Tarlac City</div>
                            <br><br>
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
                                    <td style="text-align: right; width: 15%">{{ formatNumber(as_of_balance) }}</td>
                                    <td style="width: 15%"></td>
                                </tr>
                                <tr>
                                    <td colspan="2">PAYMENTS :</td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <!-- may foreach dito -->
                                <tr v-for="payment in payments">
                                    <td style="width: 8%"></td>
                                    <td>Trans. No.: {{ payment.transaction_no }} Ref. No. {{ payment.reference_no }} - {{payment.trans_type}}</td>
                                    <td style="text-align:right;">{{formatNumber(payment.payment)}}</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td colspan="2">BALANCE :</td>
                                    <td></td>
                                    <td style="text-align: right">{{ formatNumber(previous_balance) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="2">PENALTIES :</td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>INTEREST CHARGES - 3.00%</td>
                                    <td></td>
                                    <td style="text-align: right">0.00</td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>PENALTY CHARGES - {{formatNumber(penalty.contract_rate * 100)}}%</td>
                                    <td></td>
                                    <td style="text-align: right">{{ formatNumber(penalty.billing_othr_line_total) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="2">ADJUSTMENTS :</td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <!-- may foreach dito -->
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td style="text-align: right">0.00</td>
                                    <td style="text-align: right">0.00</td>
                                </tr>
                                <tr>
                                    <td colspan="3" style="font-size: 9pt;"><b>TOTAL OUTSTANDING BALANCE</b></td>
                                    <td style="text-align: right; border-top: 1px solid gray"><b>{{ formatNumber(Number(previous_balance) + (Number(previous_balance * 0.03))) }}</b></td>
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
                                    <td colspan="4">RENTS</td>
                                </tr>
                                <tr>
                                    <td style="width: 8%"></td>
                                    <td colspan="3"></td>
                                    <td style="text-align: right; width: 15%">BASIC RENT</td>
                                    <td style="text-align: right; width: 15%">DISCOUNTED RENT</td>
                                </tr>
                                <tr>
                                    <td colspan="4">RENTAL :</td>
                                    <td style="text-align: right">{{formatNumber(billing.total_fixed_rent)}}</td>
                                    <td style="text-align: right">{{formatNumber(billing.contract_discounted_rent)}}</td>
                                </tr>
                                <tr>
                                    <td colspan="2"></td>
                                    <td style="text-align: right">RATE</td>
                                    <td style="text-align: right">READING</td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td colspan="2">UTILITIES :</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <!-- may foreach dito -->
                                <tr v-for="utility in utilities">
                                    <td></td>
                                    <td>{{ utility.charge_desc }} <i>{{ utility.contract_notes }}</i> </td>
                                    <td style="text-align: right">{{ formatNumber(utility.contract_rate) }}</td>
                                    <td style="text-align: right">{{ formatNumber(utility.contract_default_reading) }}</td>
                                    <td style="text-align: right">{{ formatNumber(utility.billing_util_line_total) }}</td>
                                    <td style="text-align: right">{{ formatNumber(utility.billing_util_line_total) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="2">MISCELLANEOUS CHARGES :</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <!-- may foreach dito -->
                                <tr v-for="misc in miscellaneous">
                                    <td></td>
                                    <td>{{ misc.charge_desc }} <i>{{ misc.contract_notes }}</i></td>
                                    <td style="text-align: right">{{ formatNumber(misc.contract_rate) }}</td>
                                    <td style="text-align: right">{{ formatNumber(misc.contract_default_reading) }}</td>
                                    <td style="text-align: right">{{ formatNumber(misc.billing_misc_line_total) }}</td>
                                    <td style="text-align: right">{{ formatNumber(misc.billing_misc_line_total) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="2">OTHER CHARGES :</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <!-- may foreach dito -->
                                <tr v-for="othr in filterOther(2)">
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
                                    <td style="text-align: right">({{ formatNumber(billing.contract_discounted_rent * (billing.wtax_percent / 100)) }})</td>
                                </tr>
                                 <tr>
                                    <td></td>
                                    <td>VAT</td>
                                    <td style="text-align: right">{{ formatNumber(billing.vat_percent) }}%</td>
                                    <td></td>
                                    <td style="text-align: right">{{ formatNumber(billing.total_vat) }}</td>
                                    <td style="text-align: right">{{ formatNumber(billing.discounted_vatable_amount * (billing.vat_percent / 100)) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="4" style="font-size: 9pt;"><b>TOTAL CURRENT CHARGES</b></td>
                                    <td style="text-align: right; border-top: 1px solid gray"><b>{{ formatNumber(Number(billing.total_amount_due) - Number(penalty.billing_othr_line_total)) }}</b></td>
                                    <td style="text-align: right; border-top: 1px solid gray"><b>{{ formatNumber(Number(billing.contract_discounted_rent) + (Number(billing.total_util_charges) - Number(penalty.billing_othr_line_total)) + Number(billing.total_misc_charges) + Number(billing.total_othr_charges) + Number(billing.discounted_vatable_amount * (billing.vat_percent / 100)) - Number(billing.contract_discounted_rent * (billing.wtax_percent / 100))) }}</b></td>
                                </tr>
                            </tbody>
                        </table>
                        <table class="total" style="width: 100%; margin-top: 5px;">
                            <thead>
                                <tr>
                                    <th style="text-align: left; width: 70%"><h2>TOTAL AMOUNT DUE</h2></th>
                                    <th style="text-align: right; width: 15%;"><h2>{{ formatNumber(Number(previous_balance) + (Number(billing.total_amount_due) )) }}</h2></th>
                                    <th style="text-align: right; width:15%;"><h2>{{ formatNumber(Number(previous_balance) + (Number(billing.contract_discounted_rent) + (Number(billing.total_util_charges) - Number(penalty.billing_othr_line_total)) + Number(billing.total_misc_charges) + Number(billing.total_othr_charges) + Number(billing.discounted_vatable_amount * (billing.vat_percent / 100)) - Number(billing.contract_discounted_rent * (billing.wtax_percent / 100)) + (Number(previous_balance * 0.03)))) }}</h2></th>
                                </tr>
                            </thead>
                        </table>
                        <div style="width: 100%">
                            * NOTE
                            <ol>
                                <li>The shaded column shall be paid when LESSEE does not pay within 5 calendar days from receipt of this statement.</li>
                                <li>Have all cheque payment be addressed to SMCT Realty & Holdings Co., Inc.</li>
                                <li>Payments shall be made at the Mall Administration Office without demand.</li>
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
            billing: [],
            payments: [],
            utilities: [],
            miscellaneous: [],
            other: [],
            previous_balance: null,
            as_of_balance: null,
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
        await this.$http.get('api/billing/' + this.billing_id +'/' + true, {
                headers: {
                        Authorization: 'Bearer ' + localStorage.getItem('token')
                    }
            })
            .then((response) => {
                const res = response.data.data
                this.billing = res
                var pmonth = new Date(this.billing.app_year + '-' + Number(this.billing.month_id - 1) +  '-1')
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
                this.utilities = res.util_charges
                this.miscellaneous = res.misc_charges
                this.other = res.othr_charges
            })
            .catch(error => {
              if (!error.response) return
              console.log(error)
            })
        await this.filterOther(1)
        await this.d.print(this.$refs.soa, this.cssText)
    },
    mounted(){
        const { Printd } = window.printd
        this.d = new Printd()

        const { contentWindow } = this.d.getIFrame()
        // contentWindow.addEventListener(
        //     'afterprint', () => window.close()
        // )
    },
    methods: {
        filterOther(charge_id){
            if(charge_id == 1){
                this.other.filter(u => {
                    if(u.charge_id == 1){
                        this.penalty.contract_rate = u.contract_rate
                        this.penalty.billing_othr_line_total = u.billing_othr_line_total
                    }
                })
            }
            else{
                return this.other.filter(u => u.charge_id !== 1)
            }    
        }
    },
  }
</script>

