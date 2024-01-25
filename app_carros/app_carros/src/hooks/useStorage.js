import AsyncStorage from '@react-native-async-storage/async-storage';

const useStorage = () => {

    const getItem = async (key) => {
        try{
            const carros = await AsyncStorage.getItem(key);
            return JSON.parse(carros) || [];
        }catch(error){
            console.log("Erro", error)
            return [];
        }
    }

    const saveItem = async(key, value) => {
        try{
            let carros = await getItem(key);

            carros.push(value)

            await AsyncStorage.setItem(key, JSON.stringify(carros))

        }catch(error){ 
            console.log("ERRO AO SALVAR", error)
        }
    }

    return {
        getItem,
        saveItem
    }
}

export default useStorage;