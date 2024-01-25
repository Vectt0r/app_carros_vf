import React, { useState, useEffect } from 'react';
import { View, Text, StyleSheet, TouchableOpacity, KeyboardAvoidingView, FlatList, Platform } from 'react-native';

const HomeScreen = ({ route, navigation }) => {
  const [dadosCarros, setDadosCarros] = useState([]);
  const [dataAtual, setDataAtual] = useState('');

  useEffect(() => {
    if (route.params && route.params.dadosCarros) {
      setDadosCarros(prevDadosCarros => [...prevDadosCarros, route.params.dadosCarros]);
    }

  // Atualizar a data atual quando o componente for montado
    const data = new Date();
    const formatoData = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    const dataFormatada = data.toLocaleDateString('pt-BR', formatoData);
    setDataAtual(dataFormatada);
  }, [route.params]);

  // limpar a lista de carros
  const limparListaCarros = () => {
    setDadosCarros([]);
  };

  const handleNavigate = () => {
    navigation.navigate('Iniciar Corrida');
  };

  const handleDadosCarrosPress = (dadosCarros) => {
    navigation.navigate('Finalizar', { dadosCarros, limparListaCarros }); // Passa a função de limpar a lista para a tela 'Finalizar'
  };

  const adicionarHabilitado = dadosCarros.length === 0; // Verifica se já há itens cadastrados

  return (
    <View style={styles.container}>
      <KeyboardAvoidingView behavior={Platform.OS === 'ios' ? 'padding' : 'height'} style={styles.container}>
          <Text style={styles.title}>{dataAtual}</Text>

          {/* Lista de carros usando FlatList */}
          <FlatList
            data={dadosCarros}
            keyExtractor={(item, index) => index.toString()}
            renderItem={({ item }) => (
              <TouchableOpacity onPress={() => handleDadosCarrosPress(item)}>
                <View style={styles.dadosCarrosItem}>
                  <Text style={styles.dataText}>{`Hora Saida: ${item.horaSaida}`}</Text>
                  <Text style={styles.dataText}>{`Funcionario: ${item.nome}`}</Text>
                  <Text style={styles.dataText}>{`Placa: ${item.placa}`}</Text>
                  <Text style={styles.dataText}>{`Cidade: ${item.cidade}`}</Text>
                  <Text style={styles.dataText}>{`Localidade: ${item.localidade}`}</Text>
                  <Text style={styles.dataStatus}>{`${item.status}`}</Text>                
                </View>
              </TouchableOpacity>
            )}
          />
          
          {/* Botão de Adicionar Nova Corrida */}
          <TouchableOpacity
            style={[styles.buttonContainer, { backgroundColor: adicionarHabilitado ? '#4169E1' : 'gray' }]}
            onPress={adicionarHabilitado ? handleNavigate : null}
            disabled={!adicionarHabilitado}
          >
            <Text style={styles.buttonText}>Adicionar</Text>
          </TouchableOpacity>
      </KeyboardAvoidingView>
    </View>
  );
};

const styles = StyleSheet.create({
  container: {
    padding: 10,
    backgroundColor: '#fff',
    elevation: 2,
    flex: 1,
    justifyContent: 'center',
    textAlign: 'center',
    borderRadius: 10,
  },
  scrollViewContent: {
    flexGrow: 1,
  },
  title: {
    fontSize: 24,
    marginBottom: 16,
    justifyContent: 'center',
    textAlign: 'center',
  },
  dadosCarrosItem: {
    marginBottom: 10,
    padding: 10,
    backgroundColor: '#f0f0f0',
    borderRadius: 5,
    borderWidth:0.8,
  },
  dataText: {
    fontSize: 16,
    color: '#333',

  },
  buttonContainer: {
    backgroundColor: '#4169E1',
    borderRadius: 5,
    paddingVertical: 10,
    paddingHorizontal: 20,
    marginTop: 20,
  },
  buttonText: {
    color: 'white',
    fontSize: 18,
    textAlign: 'center',
  },
  dataStatus:{
    fontSize: 16,
    color: 'red',
  }
});

export default HomeScreen;
