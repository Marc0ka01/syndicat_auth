import axios from 'axios'
import { createWorker } from 'tesseract.js'
import { API_GET_SYNDICAT, API_SYNDICAT_ADD, API_SYNDICAT_PAIE } from './constant';

// fonction qui permet d'appeler l'api d'insertion des utilisateurs
export async function syndicat_add(nom, prenom, date, lieu, domicile, contact, categorie, fonction, num_permis, num_cni, sang) {
    const formData = new FormData();
    formData.append("nom", nom);
    formData.append("prenom", prenom);
    formData.append("date_naissance", date);
    formData.append("lieu_naissance", lieu);
    formData.append("domicile", domicile);
    formData.append("contact", contact);
    formData.append("categorie", categorie);
    formData.append("fonction", fonction);
    formData.append("num_permis", num_permis);
    formData.append("num_cni", num_cni);
    formData.append("sang", sang);

    // requête api
    const response = await axios.post(API_SYNDICAT_ADD, formData, {
        method: 'POST',
        headers: {
            "Content-Type": "multipart/form-data"
        }
    });
    return response.data;
};

// foncion qui permet de chercher un utilisateur
export async function syndicat_fetch(id){
    const response = await axios.get(`${API_GET_SYNDICAT}?id_syndicat=${id}`, {
        method:'GET',
        headers:{
            "Content-Type":"application/json"
        }
    });

    return response.data;
}

// fonction qui permet d'appeler l'api de paiment
export async function syndicat_paie() {

    // requête api
    const response = await axios.get(API_SYNDICAT_PAIE, {
        method: 'GET',
        headers: {
            "Content-Type": "application/json"
        }
    });

    return response.data;
};

// scan ocr
export async function performOCR(imageUrl) {
    const worker = await createWorker();
    await worker.load();
    await worker.loadLanguage('eng');
    await worker.initialize('eng');
    const { data: { text } } = await worker.recognize(imageUrl);
    await worker.terminate();
    return text;
};