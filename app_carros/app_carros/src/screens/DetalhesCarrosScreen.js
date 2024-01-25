import React, { useState, useEffect } from 'react';
import { View, Text, TextInput, StyleSheet, TouchableOpacity, ScrollView } from 'react-native';

const DetalhesCarrosScreen = ({ route, navigation }) => {
  const { dadosCarros } = route.params;
  const [horaChegada, setHoraChegada] = useState('');
  const [kmFinal, setKmFinal] = useState('');
  const [cidadeDois, setCidadeDois] = useState('');
  const [localidadeDois, setLocalidadeDois] = useState('');
  const [nomeFuncionario] = useState(dadosCarros.nome);
  const [isFormValid, setIsFormValid] = useState(false);
  
  const apiUrl = 'http://172.20.15.149:3000/api/encerrarCorrida';

  useEffect(() => {
    setHoraChegada(formatarHoraBrasil());
  }, []);

  useEffect(() => {
    setIsFormValid(kmFinal);
  }, [kmFinal]);

  const formatarHoraBrasil = () => {
    const options = {
      hour: '2-digit',
      minute: '2-digit',
      second: '2-digit',
      timeZone: 'America/Sao_Paulo'
    };
    const horaChegada = new Date().toLocaleString('pt-BR', options);
    return horaChegada;
  };

  const encerrarCorrida = async () => {
    try {
      if (parseFloat(kmFinal) <= parseFloat(dadosCarros.kmInicial)) {
        alert('O valor do KM final não pode ser menor ou igual ao KM inicial.');
        return;
      }
  
    //Formatação da data no formato internacional padrão ('YYYY-MM-DD')
    const dataFormatada = new Date().toISOString().slice(0, 10);

    //Formatação da hora no formato 'HH:mm:ss'
    const horaChegadaFormatada = formatarHoraBrasil();

    const dadosParaEnviar = {
      ...dadosCarros,
      data: dataFormatada,
      kmFinal,
      horaChegada: horaChegadaFormatada,
      cidadeDois,
      localidadeDois,
    };
  
      console.log(nomeFuncionario);
  
      const response = await fetch(apiUrl, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(dadosParaEnviar),
      });
  
      if (response.ok) {
        const result = await response.json();
        if (result.success) {
          console.log('Dados inseridos com sucesso:', result);
          navigation.reset({
            index: 0,
            routes: [{ name: 'Home' }],
          });
        } else {
          console.error('Erro ao inserir dados:', result.error);
        }
      } else {
        console.error('Erro HTTP:', response.status, response.statusText);
      }
    } catch (error) {
      console.error('Erro na solicitação HTTP:', error);
    }
  };

  return (
    <View style={styles.container}>
        <ScrollView contentContainerStyle={styles.scrollViewContent} keyboardShouldPersistTaps="handled">

          <Text style={styles.label}>KM Final:</Text>
          <TextInput
            style={styles.input}
            default
            Value={dadosCarros.kmFinal}
            onChangeText={(numeric) => setKmFinal(numeric)}
            keyboardType="numeric"
          />

          <Text style={[styles.label, styles.labelGreyed]}>Cidade 1:</Text>
          <TextInput
            style={[styles.input, styles.greyedInput]}
            value={dadosCarros.cidade}
            editable={false}
          />

          <Text style={[styles.label, styles.labelGreyed]}>Localidade 1:</Text>
          <TextInput
            style={[styles.input, styles.greyedInput]}
            value={dadosCarros.localidade}
            editable={false}
          />

          <Text style={styles.label}>Cidade 2</Text>
          <TextInput
            style={styles.input}
            placeholder=""
            value={cidadeDois}  // Alterar para cidadeDois
            onChangeText={(text) => {
              setCidadeDois(text);
            }}
          />

          <Text style={styles.label}>Localidade 2</Text>
          <TextInput
            style={styles.input}
            placeholder=""
            value={localidadeDois}  // Alterar para localidadeDois
            onChangeText={(text) => {
              setLocalidadeDois(text);
            }}
          />

          <TouchableOpacity style={[styles.addButton, {backgroundColor: isFormValid ? '#B22222' : '#D3D3D3'}]}
          onPress={encerrarCorrida}
          disabled={!isFormValid}
          >
            <Text style={styles.addButtonText}>Encerrar Corrida</Text>
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
    borderRadius: 10,
  },
  addButton: {
    backgroundColor: '#B22222',
    padding: 10,
    alignItems: 'center',
    borderRadius: 4,
    marginBottom: 20,
    marginTop: 10,
  },
  greyedInput: {
    backgroundColor: '#f2f2f2',
  },
  addButtonText: {
    color: '#fff',
    fontSize: 16,
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
    color: '#777',
  },
  labelGreyed: {
    color: '#777',
  },
});

export default DetalhesCarrosScreen;