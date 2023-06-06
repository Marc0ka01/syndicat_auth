<script>
import { syndicat_paie, syndicat_fetch } from '../services.js'

export default {
    data() {
        return {
            message: '',
            data: {
                token: '',
                amount: '',
                currency: '',
                urls: ' https://syndicat-auth.web.app/card',
                urlc: ' https://syndicat-auth.web.app/echec',
                merchandeId: '',
            }
        }
    },
    created() {
        this.payement()
    },
    methods: {
        async payement() {
            try {
                const fetchResponse = await syndicat_fetch(this.$store.state.userId);
                if (fetchResponse.status === true) {
                    const response = await syndicat_paie();
                    if (response.success === true) {
                        this.data.token = response.token;
                        this.data.amount = response.amount;
                        this.data.currency = response.currency;
                        this.data.merchandeId = response.merchandid;
                    } else {
                        this.message = response.message;
                    }
                } else {
                    this.$router.push('/echec');
                    console.log(this.$store.state.userId)
                }
            } catch (error) {
                console.error(error);
            }
        }
    }
}
</script>

<template>
    <div class="container-fluid">
        <div class="absolute">
            <div class="card card-shadow" style="width: 30rem; height: 10rem;">
                <p class="text-danger" v-if="message.length !== 0">{{ message }}</p>
                <form method="post" action="https://secure.sycapay.net/checkresponsive">
                    <div class="payement-info">
                        <h5 class="card-title text-center pt-3">Effectuez le paiement</h5>
                        <div class="row">
                            <div class="col-md-4">
                                <input type="hidden" name="token" :value="data.token">
                            </div>
                            <div class="col-md-4"><input type="hidden" name="amount" :value="data.amount"></div>
                            <div class="col-md-4"><input type="hidden" name="currency" :value="data.currency"></div>
                        </div>

                        <p class="text-center my-3">{{ data.amount }} {{ data.currency }}</p>

                        <div class="row">
                            <div class="col-md-4"><input type="hidden" name="urls" :value="data.urls"></div>
                            <div class="col-md-4"><input type="hidden" name="urlc" :value="data.urlc"></div>
                            <div class="col-md-4"><input type="hidden" name=" commande" value="COMTEST"></div>
                        </div>
                        <div class="row">
                            <div class="col-md-6"><input type="hidden" name="merchandid" :value="data.merchandeId"></div>
                            <div class="col-md-6"><input type="hidden" name="typpaie" value=" payement"></div>
                        </div>
                        <div class="d-flex justify-content-center align-items-center">
                            <input type="submit" class="btn btn-primary" value="valider" />
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<style scoped>
.absolute {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}

.card-shadow {
    box-shadow: rgba(100, 100, 111, 0.2) 0px 7px 29px 0px;
}
</style>