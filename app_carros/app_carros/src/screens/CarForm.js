import React, { useState, useEffect } from 'react';
import { View, TextInput, Text, StyleSheet, TouchableOpacity, ScrollView } from 'react-native';
import useStorage from '../hooks/useStorage';
import { useNavigation } from '@react-navigation/native';

const CarForm = ({}) => {
  const { saveItem } = useStorage();
  const [dataAtual, setData] = useState(dataAtual);
  const [placaCarro, setPlacaCarro] =useState('');
  const [nomeFuncionario, setNomeFuncionario] = useState('');
  const [kmInicial, setkmInicial] = useState('');
  const [kmFinal] = useState('');
  const [horaSaida, setHoraSaida] = useState('');
  const [horaChegada] = useState('');
  const [localidade, setLocalidade] = useState('');
  const [cidade, setCidade] = useState('');
  const [localidadeDois] = useState('');
  const [cidadeDois] = useState('');
  const [status] = useState('Em Andamento');
  const [isFormValid, setIsFormValid] = useState(false);
  const navigation = useNavigation();

  const formatarHoraBrasil = () => {
    const options = {
      hour: '2-digit',
      minute: '2-digit',      
      second: '2-digit',
      timeZone: 'America/Sao_Paulo'
    };
    const dataHoraAtual = new Date().toLocaleString('pt-BR', options);
    return dataHoraAtual;
  };

  const formatarDataBrasil = () => {
    const options = {
      year: 'numeric',
      month: '2-digit',
      day: '2-digit',
    };
    const dataAtual = new Date().toLocaleString('pt-BR', options);
    return dataAtual;
  };

  useEffect(() => {
    setData(formatarDataBrasil());
  }, []);

  useEffect(() => {
    setHoraSaida(formatarHoraBrasil());
  }, []);

  useEffect(() => {
    // Verifica se todos os campos obrigatórios estão preenchidos
    setIsFormValid(
      nomeFuncionario && 
      placaCarro && 
      kmInicial &&
      horaSaida &&
      localidade &&
      cidade
    );
  }, [nomeFuncionario, placaCarro, kmInicial, horaSaida, localidade, cidade]);

  async function handleSave() {
    try {
      await saveItem("@nomeFuncionario", nomeFuncionario);
      await saveItem("@data", dataAtual);
      await saveItem("@placa", placaCarro)
      await saveItem("@kmInicial", kmInicial);
      await saveItem("@kmFinal", kmFinal);
      await saveItem("@horaSaida", horaSaida);
      await saveItem("@horaChegada", horaChegada);
      await saveItem("@localidade", localidade);
      await saveItem("@cidade", cidade);
      await saveItem("@localidadeDois", localidadeDois);
      await saveItem("@cidadeDois", cidadeDois);
      await saveItem("@status", status);

      const dadosSalvos = { data: dataAtual, nome: nomeFuncionario, placa: placaCarro, kmInicial: kmInicial, kmFinal: kmFinal, horaSaida: horaSaida, horaChegada: horaChegada, localidade: localidade, cidade: cidade, localidadeDois: localidadeDois, cidadeDois: cidadeDois, status: status };
      navigation.navigate('Home', {
        dadosCarros: dadosSalvos,
      });

      alert("Salvo com Sucesso");

    } catch (error) {
      console.error("Erro ao salvar:", error);
    }
  }

  return (
    <View style={styles.container}>
        <ScrollView contentContainerStyle={styles.scrollViewContent} keyboardShouldPersistTaps="handled">

        <Text style={styles.label}>Nome do Funcionário</Text>
          <TextInput
            style={styles.input}
            placeholder=""
            value={nomeFuncionario}
            onChangeText={(text) => {
              const formattedText = text.replace(/[^a-zA-Z\s]/g, '');
              setNomeFuncionario(formattedText);
            }}
          />

          <Text style={styles.label}>Numero da Placa</Text>
          <TextInput
            style={styles.input}
            placeholder=""
            value={placaCarro}
            onChangeText={(text) => setPlacaCarro(text)}
          />

          <Text style={styles.label}>KM Inicial</Text>
          <TextInput
            style={styles.input}
            placeholder=""
            value={kmInicial}
            onChangeText={(text) => setkmInicial(text)}
            keyboardType="numeric"
          />

          <Text style={styles.label}>Hora Saída</Text>
          <TextInput
            style={[styles.input, !horaSaida ? styles.greyedInput : null]}
            placeholder=""
            value={horaSaida}
            onChangeText={(text) => setHoraSaida(text)}
            editable={false}
          />

          <Text style={styles.label}>Cidade</Text>
          <TextInput
            style={styles.input}
            placeholder=""
            value={cidade}
            onChangeText={(text) =>{
              const formattedText = text.replace(/[^a-zA-Z\s]/g, '');
              setCidade(formattedText)}}
          />

          <Text style={styles.label}>Localidade</Text>
          <TextInput
            style={styles.input}
            placeholder=""
            value={localidade}
            onChangeText={(text) => {
              const formattedText = text.replace(/[^a-zA-Z\s]/g, '');
              setLocalidade(formattedText)}}
          />

          <TouchableOpacity 
            style={[styles.addButton, { backgroundColor: isFormValid ? '#32CD32' : '#D3D3D3' }]}
            onPress={handleSave}
            disabled={!isFormValid}
          >
            <Text style={styles.addButtonText}>Iniciar</Text>
          </TouchableOpacity>


        </ScrollView>
    </View>
  );
};

const styles = StyleSheet.create({
  container: {
    padding: 10,
    backgroundColor: '#fff',
    elevation: 2,
    flex: 1,
    marginBottom: 130,
  },
  scrollViewContent: {
    flexGrow: 1,
  },
  label: {
    fontSize: 20,
    marginBottom: 5,
    color: '#333',
  },
  input: {
    height: 50,
    borderColor: '#ccc',
    borderWidth: 1,
    marginBottom: 10,
    paddingLeft: 8,
    borderRadius: 4,
    fontSize: 18,
  },
  greyedInput: {
    backgroundColor: '#f2f2f2',
  },
  dateTimeContainer: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    marginBottom: 10,
  },
  dateContainer: {
    flex: 1,
    marginRight: 5,
  },
  dateInput: {
    height: 40,
    borderColor: '#ccc',
    borderWidth: 1,
    paddingLeft: 8,
    borderRadius: 4,
  },
  inlineInputs: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    marginBottom: 10,
  },
  inlineInputContainer: {
    flex: 1,
    marginRight: 5,
  },
  inlineInput: {
    height: 40,
    borderColor: '#ccc',
    borderWidth: 1,
    paddingLeft: 8,
    borderRadius: 4,
  },
  inputContainer: {
    marginBottom: 10,
  },
  addButton: {
    backgroundColor: '#32CD32',
    padding: 10,
    alignItems: 'center',
    borderRadius: 4,
    marginBottom: 20,
    marginTop: 10,
  },
  addButtonText: {
    color: '#fff',
    fontSize: 16,
  },
  backButtonText: {
    color: '#fff',
    fontSize: 16,
  },
  inputContainerObs: {
    height: 150,
    marginBottom: 10,
  },
  inputObs: {
    height: 90,
    borderColor: '#ccc',
    borderWidth: 1,
    marginBottom: 10,
    paddingLeft: 8,
    borderRadius: 4,
  },
  labelObs: {
    marginBottom: 5,
    color: '#333',
  },
});

export default CarForm;
