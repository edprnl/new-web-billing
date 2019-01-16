<style>
</style>

<template>
    <!--<b-animated fade-in>  main container -->
    <div>
        <div class="animated fadeIn" ref="ackReceipt">
            <b-row>
                <b-col sm="12">
                    <b-card >
                        <div style="font-size: 11pt; width: 100%;">
                            <div style="font-size: 20pt;font-weight: 500;">Magic Star Mall</div>
                            <div style="font-size: 11pt;font-weight: 500;margin-top: 0px;">Cut-Cut 1st, Romulo Boulevard, Tarlac City</div>
                            <br><br>
                            <div style="font-size: 16pt; font-weight: 500; font-style: italic; text-align: center">ACKNOWLEDGEMENT RECEIPT</div>
                        </div>
                        <br>
                        <div style="font-size: 11pt; width: 100%;">
                            <div style="font-weight: 500;"><span style="float: left">Reference No : {{ payments.reference_no }}</span><span style="float: right">Txn Date : {{moment(payments.payment_date, 'MMMM DD, YYYY')}}</span></div>
                            <br><br>
                        </div>
                        <div style="font-size: 11pt; width: 100%; font-weight: 500;">
                            <div style="padding: 0px 50px 5px;">
                                <div style="display: inline-block; width: 25%">
                                    Receive From
                                </div>
                                <div style="display: inline-block; width: 74%">
                                    : &nbsp;&nbsp;&nbsp;{{payments.trade_name}}
                                </div>
                            </div>
                            <div style="padding: 0px 50px 5px">
                                <div style="display: inline-block; width: 25%">
                                    Address
                                </div>
                                <div style="display: inline-block; width: 74%">
                                    : &nbsp;&nbsp;&nbsp;{{payments.head_office_address}}
                                </div>
                            </div>
                            <div style="padding: 0px 50px 5px">
                                <div style="display: inline-block; width: 25%">
                                    The Sum of Pesos (Php)
                                </div>
                                <div style="display: inline-block; width: 74%">
                                    : &nbsp;&nbsp;&nbsp;{{amount_paid_words}}
                                </div>
                            </div>
                            <div style="padding: 0px 50px 5px">
                                <div style="display: inline-block; width: 25%">
                                    Amount
                                </div>
                                <div style="display: inline-block; width: 74%">
                                    : &nbsp;&nbsp;&nbsp;{{formatNumber(payments.amount_paid)}}
                                </div>
                            </div>
                            <br><br>
                            <div style="padding: 0px 50px 5px; text-align: center"><span style="float: left">Cash</span>Check<span style="float: right">Online</span></div>
                        </div>
                        <br>
                        <div style="font-size: 11pt; width: 100%; font-weight: 500;">
                            <div style="display: inline-block; width: 50px"></div>
                            <div style="display: inline-block; border: 1px solid gray; width: 43%">
                                <div style="padding: 5px">For Check Only</div>
                                <div style="padding: 5px">Check : </div>
                                <div style="padding: 5px"><span>Check No: </span><span>Amount :</span></div>
                                <div style="padding: 5px">Check Date :</div>
                            </div>
                            <div style="display: inline-block; width: 42%">
                                <div style="padding: 5px"></div>
                                <div style="padding: 5px"></div>
                                <div style="padding: 5px; text-align: center">_______________________________</div>
                                <div style="padding: 5px; text-align: center">Received By</div>
                            </div>
                            <div style="display: inline-block; width: 50px"></div>
                        </div>
                        <br>
                        <div style="font-size: 6pt; width: 100%;">
                            <div style="font-weight: 500;"><span style="float: left">Date Printed : {{ moment(new Date(),"MMMM DD, YYYY")}}</span><span style="float: right">Printed By : {{$store.state.user.firstname}}</span></div>
                            <br><br>
                        </div>
                    </b-card>
                </b-col>
            </b-row>
        </div>
    </div>
</template>
<script>
export default {
    name: 'ackReceipt',
    data () {
        return {
            payment_id: null,
            payments: [],
            amount_paid_words: null,
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
        this.payment_id = this.$route.query.payment_id
        await this.$http.get('api/payment/' + this.payment_id, {
                headers: {
                        Authorization: 'Bearer ' + localStorage.getItem('token')
                    }
            })
            .then((response) => {
                const res = response.data.data
                this.payments = res
            })
            .catch(error => {
              if (!error.response) return
              console.log(error)
            })
            await this.$http.get('api/payment/words/' + Number(this.payments.amount_paid).toFixed(2), {
                    headers: {
                        Authorization: 'Bearer ' + localStorage.getItem('token')
                    }
            })
            .then((response) => {
                var words = response.data.split(' ')
                var new_word = ''
                words.forEach(element => {
                    new_word += element.charAt(0).toUpperCase() + element.slice(1) + ' '
                });

                this.amount_paid_words = new_word
            })
            .catch(error => {
                if (!error.response) return
                console.log(error)
            })
        
        await this.d.print(this.$refs.ackReceipt, this.cssText)
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
        
    },
  }
</script>

