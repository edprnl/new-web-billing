<style >
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
                                        TOTAL TENANTS
                                    </span>
                                    <div class="info-box-number">
                                        0
                                    </div>
                                </div>
                            </div>  
                        </b-col>
                        <b-col sm="3">
                            <div class="info-box bg-success">
                                <span class="info-box-icon">
                                    <i>

                                    </i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text">
                                        TOTAL CONTRACTS
                                    </span>
                                    <div class="info-box-number">
                                        0
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
                                        NEW CONTRACTS
                                    </span>
                                    <div class="info-box-number">
                                       0
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
                                        EXPIRING CONTRACTS
                                    </span>
                                    <div class="info-box-number">
                                        0
                                    </div>
                                </div>
                            </div>  
                        </b-col>
                    </b-row>
                    <b-row sm="12">
                        <b-col sm="6">
                            <!-- <b-card>
                                <h5 slot="header">  table header
                                    <span class="text-primary">
                                        <i class="fa fa-bars"></i> 
                                        Charge List
                                        <small class="font-italic">List of all registered charges.</small></span>
                                </h5>
                            </b-card> -->
                            <b-card header="Bar Graph">
                                <div class="chart-wrapper">
                                    <bar-example/>
                                </div>
                            </b-card>
                        </b-col>
                        <b-col sm="6">
                            <!-- <b-card>
                                <h5 slot="header">  table header
                                    <span class="text-primary">
                                        <i class="fa fa-bars"></i> 
                                        Charge List
                                        <small class="font-italic">List of all registered charges.</small></span>
                                </h5>
                            </b-card> -->
                            <b-card header="Line Chart">
                                <div class="chart-wrapper">
                                    <line-example/>
                                </div>
                            </b-card>
                        </b-col>
                    </b-row>
                </b-col>
            </b-row>
        </div>
    </div>
    
</template>
