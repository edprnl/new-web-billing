<style scoped>
    .info-box {
        display: block;
        min-height: 90px;
        background: #fff;
        width: 100%;
        box-shadow: 0 1px 1px rgba(0,0,0,0.1);
        border-radius: 2px;
        margin-bottom: 15px;
    }   
    .info-box-icon {
        border-top-left-radius: 2px;
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
        border-bottom-left-radius: 2px;
        display: block;
        float: left;
        height: 90px;
        width: 90px;
        text-align: center;
        font-size: 45px;
        line-height: 90px;
        background: rgba(0,0,0,0.2);
    }
    .info-box-content {
        text-align: center;
        vertical-align: middle;
        display: inherit;
    }
    .info-box-text {
        text-transform: uppercase;
       
    }
    .info-box-number {
        display: block;
        font-weight: bold;
        font-size: 18px;
    }
</style>

<template>
    <div>
        <div class="animated fadeIn"> <!-- main div -->
            <b-row>
                <b-col sm="12">
                    <b-row sm="12">
                        <b-col sm="3">
                            <div class="info-box bg-primary" >
                                <span class="info-box-icon bg-aqua">
                                    <i class="fa fa-users"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text">
                                        <h6>TOTAL TENANTS</h6>
                                    </span>
                                    <div class="info-box-number">
                                        <h3>{{no_of_tenants}}</h3>
                                    </div>
                                </div>
                            </div>  
                        </b-col>
                        <b-col sm="3">
                            <div class="info-box bg-success">
                                <span class="info-box-icon">
                                    <i class="fa fa-paperclip"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text">
                                        <h6>TOTAL CONTRACTS</h6>
                                    </span>
                                    <div class="info-box-number">
                                        <h3>{{no_of_contracts}}</h3>
                                    </div>
                                </div>
                            </div>  
                        </b-col>
                        <b-col sm="3">
                            <div class="info-box bg-warning">
                                <span class="info-box-icon">
                                    <i>

                                    </i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text">
                                        <h6>NEW CONTRACTS</h6>
                                    </span>
                                    <div class="info-box-number">
                                        <h3>0</h3>
                                    </div>
                                </div>
                            </div>  
                        </b-col>
                        <b-col sm="3">
                            <div class="info-box bg-danger">
                                <span class="info-box-icon">
                                    <i>

                                    </i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text">
                                        <h6>EXPIRING CONTRACTS</h6>
                                    </span>
                                    <div class="info-box-number">
                                        <h3>{{no_of_expiring_contracts}}</h3>
                                    </div>
                                </div>
                            </div>  
                        </b-col>
                    </b-row>
                    <b-row sm="12">
                        <b-col sm="6">
                            <b-card header="Payments" style="min-height: 420px;">
                                <b-row class="mb-2">
                                    <b-col sm="4">
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
                                        <label class="col-form-label">to</label>
                                    </b-col>
                                    <b-col sm="4">
                                        <date-picker
                                            @input="filterPayments()"
                                            v-model="date_to" 
                                            lang="en" 
                                            input-class="form-control mx-input"
                                            format="MMMM DD, YYYY"
                                            :clearable="false">
                                        </date-picker>
                                    </b-col>
                                    <b-col  sm="3">
                                        <b-form-input 
                                                    v-model="filters.payments.criteria" 
                                                    type="text" 
                                                    placeholder="Search">
                                        </b-form-input>
                                    </b-col>
                                </b-row>
                                <b-table 
                                    :filter="filters.payments.criteria"
                                    :fields="tables.payments.fields"
                                    :items.sync="tables.payments.items"
                                    :current-page="paginations.payments.currentPage"
                                    :per-page="paginations.payments.perPage"
                                    @filtered="onFiltered($event,'payments')"
                                    striped hover small bordered show-empty
                                    responsive
                                >
                                    
                                </b-table>
                                <b-pagination
                                            :align="'right'"
                                            :total-rows="paginations.payments.totalRows"
                                            :per-page="paginations.payments.perPage"
                                            v-model="paginations.payments.currentPage" />
                            </b-card>
                        </b-col>
                        <b-col sm="6">
                            <b-card header="Line Chart" style="min-height: 420px;">
                                <b-row class="justify-content-md-center">
                                    <b-form-radio-group @input="filterPaymentLine()" v-model="payment_type">
                                        <b-form-radio value="-1" id="all"><label class="col-form-label" for="all">All</label></b-form-radio>
                                        <b-form-radio value="0" id="cash"><label class="col-form-label" for="cash">Cash</label></b-form-radio>
                                        <b-form-radio value="1" id="check"><label class="col-form-label" for="check">Check</label></b-form-radio>
                                        <b-form-radio value="2" id="online"><label class="col-form-label" for="online">Online</label></b-form-radio>
                                    </b-form-radio-group>
                                </b-row>
                                <div class="chart-wrapper">
                                    <line-example :data="line_data" :label="'Monthly Collection ' + moment(new Date, 'YYYY')" :height="250"/>
                                </div>
                            </b-card>
                        </b-col>
                    </b-row>
                </b-col>
            </b-row>
        </div>
    </div>
    
</template>
<script>
export default {
    name: 'dashboard',
    data () {
      return {
            payment_type: -1,
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
                            key:'amount_paid',
                            label: 'Amount Paid',
                            thClass: 'text-right',
                            tdClass: 'text-right align-middle',
                            formatter: (value) => {
                                return this.formatNumber(value)
                            }
                        }
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
                    perPage: 8
                }
            },
            date_from: moment().startOf('month').format('YYYY-MM-DD'),
            date_to: moment().endOf('month').format('YYYY-MM-DD'),
            line_data: [],
            no_of_tenants: 0,
            no_of_contracts: 0,
            no_of_expiring_contracts: 0,
        }
    },
    methods: {
        filterPayments(){
            var from = this.moment(this.date_from, 'YYYY-MM-DD')
            var to = this.moment(this.date_to, 'YYYY-MM-DD')
            this.filterTableList('payments', from, to)
        },
        filterPaymentLine(){
            this.$http.get('api/dashboard/payment/'+this.payment_type+'/'+false,{
            headers: {
                    Authorization: 'Bearer ' + localStorage.getItem('token')
                }
            })
            .then((response) => {  
                var data = response.data
                var dArray = []
                data.forEach(element => {
                    dArray.push(element.amount)
                });
                this.line_data = dArray
            }).catch(error => {
                console.log(error)
            })
        }
    },
    created () {
        this.filterTableList('payments', this.date_from, this.date_to)
        this.$http.get('api/dashboard/index/'+this.payment_type,{
            headers: {
                    Authorization: 'Bearer ' + localStorage.getItem('token')
                }
        })
        .then((response) => {  
            var data = response.data
                this.no_of_tenants = data.tenants[0].no_of_tenants
                this.no_of_contracts = data.contracts[0].no_of_contracts
                this.no_of_expiring_contracts = data.expiring_contracts[0].no_of_expiring_contracts
                var dArray = []
                data.payment_line.forEach(element => {
                    dArray.push(element.amount)
                });
                this.line_data = dArray
            
        }).catch(error => {
            console.log(error)
        })
    }
}
</script>