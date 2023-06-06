<script>
import { performOCR, syndicat_add } from '../services.js'

export default {
    data() {
        return {
            gr_sang: ['O-', 'O+', 'A-', 'A+', 'B-', 'B+', 'AB-', 'AB+'],
            message: '',
            status: false,
            data: {
                nom: '',
                prenom: '',
                date_naissance: '',
                lieu_naissance: '',
                domicile: '',
                contact: '',
                categorie: '',
                fonction: '',
                num_permis: '',
                num_cni: '',
                permis_expire: '',
                cni_expire: '',
                sang: '',
            },
            img: {
                permis_recto: null,
                permis_verso: null,
                cni_recto: null,
                cni_verso: null,
            },
            handle: {
                permis_recto_url: null,
                permis_verso_url: null,
                cni_recto_url: null,
                cni_verso_url: null,
                date_n: '',
                cni_e: '',
            }
        }
    },
    updated() {
        const form = this.$refs.form;
        const nextBtn = this.$refs.nextBtn;
        const backBtn = this.$refs.backBtn;
        const allInput = this.$refs.form.querySelectorAll(".first input");

        nextBtn.addEventListener("click", () => {
            allInput.forEach((input) => {
                if (input.value !== "") {
                    form.classList.add("secActive");
                } else {
                    form.classList.remove("secActive");
                }
            });
        });

        backBtn.addEventListener("click", () => {
            form.classList.remove("secActive");
        });
    },
    methods: {
        // chargement des images
        onPermisRectoChange(event) {
            this.img.permis_recto = event.target.files[0];
            this.handle.permis_recto_url = URL.createObjectURL(this.img.permis_recto)
        },
        onPermisVersoChange(event) {
            this.img.permis_verso = event.target.files[0];
            this.handle.permis_verso_url = URL.createObjectURL(this.img.permis_verso)
        },
        onCniRectoChange(event) {
            this.img.cni_recto = event.target.files[0];
            this.handle.cni_recto_url = URL.createObjectURL(this.img.cni_recto)
        },
        onCniVersoChange(event) {
            this.img.cni_verso = event.target.files[0];
            this.handle.cni_verso_url = URL.createObjectURL(this.img.cni_verso)
        },
        // Méthode pour convertir le format de la date
        convertDateFormat(date) {
            // Sépare la date en année, mois et jour
            const [year, month, day] = date.split('/');

            // Crée une nouvelle date au format jj/mm/aaaa
            const formattedDate = `${day}/${month}/${year}`;

            return formattedDate;
        },

        // Méthode pour traiter la date lors du changement
        onDateNaissanceChange() {
            const date_n_update = this.data.date_naissance.replace(/-/g, '/')
            const formattedDate = this.convertDateFormat(date_n_update);
            this.handle.date_n = formattedDate;
        },
        onCNIExpireChange() {
            const cni_e_update = this.data.cni_expire.replace(/-/g, '/')
            const formattedDate = this.convertDateFormat(cni_e_update);
            this.handle.cni_e = formattedDate;
        },
        // 
        async addSyndicat() {
            try {
                const permisRectoText = await performOCR(this.handle.permis_recto_url);
                const permisVersoText = await performOCR(this.handle.permis_verso_url);
                const cniRectoText = await performOCR(this.handle.cni_recto_url);
                const cniVersoText = await performOCR(this.handle.cni_verso_url);

                // replace letter
                this.data.num_cni = this.data.num_cni.replace(/i/gi, "l");

                // Comparaison des valeurs extraites avec les données du formulaire
                if (
                    cniRectoText.includes(this.data.nom) &&
                    cniRectoText.includes(this.data.prenom) &&
                    cniRectoText.includes(this.handle.date_n) &&
                    cniRectoText.includes(this.data.lieu_naissance) &&
                    cniRectoText.includes(this.handle.cni_e)
                ) {
                    // Les valeurs correspondent, vous pouvez procéder à la soumission du formulaire
                    // ...
                    console.log("yes")

                    // Soumission du formulaire
                    const response = await syndicat_add(this.data.nom, this.data.prenom, this.data.date_naissance, this.data.lieu_naissance, this.data.domicile, this.data.contact, this.data.categorie, this.data.fonction, this.data.num_permis, this.data.num_cni, this.data.sang);

                    // on vérifie si l'insertion a été éffectué
                    if (response.status === true) {
                        // En registrement de l'ID de l'utilisateur
                        const userId = response.syndicat_id
                        this.$store.commit('setUserId', userId);

                        this.$router.push('/payement')
                    } else {
                        this.message = "Une erreur est survenue lors de l'enregistrement des informations"
                        console.log('erreur')
                    }
                } else {
                    // Les valeurs ne correspondent pas, affichez un message d'erreur ou effectuez une autre action
                    // ...
                    console.log('no')
                    console.log(this.data.num_cni)
                    console.log(this.data.nom)
                    console.log(this.data.prenom)
                    console.log(this.handle.date_n)
                    console.log(this.handle.cni_e)
                    console.log(this.data.lieu_naissance)

                    // 
                    console.log(cniRectoText)
                }
            } catch (error) {
                console.log(error)
            }
        },

        validateNumero() {
            // Expression régulière pour vérifier si le champ contient uniquement des chiffres
            const regex = /^\d+$/;

            if (!regex.test(this.data.contact)) {
                this.status = true;
            } else {
                this.status = false;
            }
        }

    },
};
</script>

<template>
    <div class="body">
        <div class="container">
            <header>Registration</header>
            <form action="#" ref="form" @submit.prevent="addSyndicat">
                <div class="form first">
                    <div class="details personal">
                        <span class="title">Détails personnel</span>
                        <div class="fields">
                            <div class="input-field">
                                <label>Nom</label>
                                <input type="text" placeholder="Entrer votre nom" required v-model="data.nom">
                            </div>
                            <div class="input-field">
                                <label>Prénom</label>
                                <input type="text" placeholder="Entrer votre prénom" required v-model="data.prenom">
                            </div>
                            <div class="input-field">
                                <label>Date de naissance</label>
                                <input type="date" placeholder="Entrer votre date de naissance" required
                                    v-model="data.date_naissance" @change="onDateNaissanceChange">
                            </div>
                            <div class="input-field">
                                <label>Lieu</label>
                                <input type="text" placeholder="Entrer le lieu de naissance" required
                                    v-model="data.lieu_naissance">
                            </div>
                            <div class="input-field">
                                <label>Contact</label>
                                <input type="text" placeholder="Entrer votre numero de téléphone" minlength="10"
                                    maxlength="10" required v-model="data.contact" @input="validateNumero">
                                <p class="text-danger" v-if="status">Veuillez s'il vous plait entrer que des chiffres</p>
                            </div>
                            <div class="input-field">
                                <label>Groupe sanguin</label>
                                <select required v-model="data.sang">
                                    <option disabled selected>Selectionner un groupe sanguin</option>
                                    <option v-for="gr in gr_sang" :value="gr">{{ gr }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="details ID">
                        <span class="title">Détails sur l'identité</span>
                        <div class="fields">
                            <div class="input-field">
                                <label>Numéro permis</label>
                                <input type="text" placeholder="Entre le numero de votre permis" required
                                    v-model="data.num_permis">
                            </div>
                            <div class="input-field">
                                <label>Numéro cni</label>
                                <input type="text" placeholder="Entrer le numéro de votre cni" required
                                    v-model="data.num_cni">
                            </div>
                            <div class="input-field">
                                <label>Catégorie de transport</label>
                                <input type="text" placeholder="Ex: taxi communal" required v-model="data.categorie">
                            </div>
                            <div class="input-field">
                                <label>Fonction</label>
                                <input type="text" placeholder="Entrer votre fonction" required v-model="data.fonction">
                            </div>
                            <div class="input-field">
                                <label>Date d'expiration permis</label>
                                <input type="date" placeholder="Entrer la date d'expiration" required
                                    v-model="data.permis_expire">
                            </div>
                            <div class="input-field">
                                <label>Date d'expiration cni</label>
                                <input type="date" placeholder="Entrer la date d'expiration" required
                                    v-model="data.cni_expire" @change="onCNIExpireChange">
                            </div>
                        </div>
                        <button class="nextBtn" href="#" ref="nextBtn">
                            <span class="btnText">Next</span>
                            <i class="uil uil-navigator"></i>
                        </button>
                    </div>
                </div>
                <div class="form second">
                    <div class="details address">
                        <span class="title">Address Details</span>
                        <div class="fields">
                            <div class="input-field">
                                <label>Domicile</label>
                                <input type="text" placeholder="Où residez vous ?" required v-model="data.domicile">
                            </div>

                        </div>
                    </div>
                    <div class="details family">
                        <span class="title">Justificatifs</span>
                        <div class="fields">
                            <div class="input-field">
                                <label>Photo permis recto</label>
                                <img v-if="handle.permis_recto_url" :src="handle.permis_recto_url" class="img-thumbnail"
                                    alt="..." width="50" />
                                <input class="form-control" type="file" accept="image/*" required
                                    @change="onPermisRectoChange">
                            </div>
                            <div class="input-field">
                                <label>Photo permis verso</label>
                                <img v-if="handle.permis_verso_url" :src="handle.permis_verso_url" class="img-thumbnail"
                                    alt="..." width="50" />
                                <input class="form-control" type="file" accept="image/*" required
                                    @change="onPermisVersoChange">
                            </div>
                            <div class="input-field">
                                <label>Photo cni recto</label>
                                <img v-if="handle.cni_recto_url" :src="handle.cni_recto_url" class="img-thumbnail" alt="..."
                                    width="50" />
                                <input class="form-control" type="file" accept="image/*" required
                                    @change="onCniRectoChange">
                            </div>
                            <div class="input-field">
                                <label>Photo cni verso</label>
                                <img v-if="handle.cni_verso_url" :src="handle.cni_verso_url" class="img-thumbnail" alt="..."
                                    width="50" />
                                <input class="form-control" type="file" accept="image/*" required
                                    @change="onCniVersoChange">
                            </div>
                        </div>
                        <div class="buttons">
                            <div class="backBtn" ref="backBtn">
                                <i class="uil uil-navigator"></i>
                                <span class="btnText">Back</span>
                            </div>

                            <button class="sumbit" type="submit">
                                <span class="btnText">Submit</span>
                                <i class="uil uil-navigator"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<style scoped>
.body {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #4070f4;
}

.container {
    position: relative;
    max-width: 900px;
    width: 100%;
    border-radius: 6px;
    padding: 30px;
    margin: 0 15px;
    background-color: #fff;
    box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
}

.container header {
    position: relative;
    font-size: 20px;
    font-weight: 600;
    color: #333;
}

.container header::before {
    content: "";
    position: absolute;
    left: 0;
    bottom: -2px;
    height: 3px;
    width: 27px;
    border-radius: 8px;
    background-color: #4070f4;
}

.container form {
    position: relative;
    margin-top: 16px;
    min-height: 490px;
    background-color: #fff;
    overflow: hidden;
}

.container form .form {
    position: absolute;
    background-color: #fff;
    transition: 0.3s ease;
}

.container form .form.second {
    opacity: 0;
    pointer-events: none;
    transform: translateX(100%);
}

form.secActive .form.second {
    opacity: 1;
    pointer-events: auto;
    transform: translateX(0);
}

form.secActive .form.first {
    opacity: 0;
    pointer-events: none;
    transform: translateX(-100%);
}

.container form .title {
    display: block;
    margin-bottom: 8px;
    font-size: 16px;
    font-weight: 500;
    margin: 6px 0;
    color: #333;
}

.container form .fields {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
}

form .fields .input-field {
    display: flex;
    width: calc(100% / 3 - 15px);
    flex-direction: column;
    margin: 4px 0;
}

.input-field label {
    font-size: 12px;
    font-weight: 500;
    color: #2e2e2e;
}

.input-field input,
select {
    outline: none;
    font-size: 14px;
    font-weight: 400;
    color: #333;
    border-radius: 5px;
    border: 1px solid #aaa;
    padding: 0 15px;
    height: 42px;
    margin: 8px 0;
}

.input-field input :focus,
.input-field select:focus {
    box-shadow: 0 3px 6px rgba(0, 0, 0, 0.13);
}

.input-field select,
.input-field input[type="date"] {
    color: #707070;
}

.input-field input[type="date"]:valid {
    color: #333;
}

.container form button,
.backBtn {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 45px;
    max-width: 200px;
    width: 100%;
    border: none;
    outline: none;
    color: #fff;
    border-radius: 5px;
    margin: 25px 0;
    background-color: #4070f4;
    transition: all 0.3s linear;
    cursor: pointer;
}

.container form .btnText {
    font-size: 14px;
    font-weight: 400;
}

form button:hover {
    background-color: #265df2;
}

form button i,
form .backBtn i {
    margin: 0 6px;
}

form .backBtn i {
    transform: rotate(180deg);
}

form .buttons {
    display: flex;
    align-items: center;
}

form .buttons button,
.backBtn {
    margin-right: 14px;
}

@media (max-width: 750px) {
    .container form {
        overflow-y: scroll;
    }

    .container form::-webkit-scrollbar {
        display: none;
    }

    form .fields .input-field {
        width: calc(100% / 2 - 15px);
    }
}

@media (max-width: 550px) {
    form .fields .input-field {
        width: 100%;
    }
}
</style>