import React, { useState } from 'react';
import { View, Text, TextInput, TouchableOpacity, Image, StyleSheet, StatusBar } from 'react-native';
import AsyncStorage from '@react-native-async-storage/async-storage';

const LoginScreen = ({ navigation }) => {
  const [username, setUsername] = useState('');
  const [password, setPassword] = useState('');

  const apiUrl = 'http://172.20.15.149:3000/api/login';

  const handleLogin = async () => {
    try {
      const response = await fetch(apiUrl, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          nome: username,
          senha: password,
        }),
      });

      const data = await response.json();

      if (response.ok) {
        // Certifique-se de que o token não é undefined antes de armazená-lo
        if (data.token) {
          await AsyncStorage.setItem('accessToken', data.token); // Modifique conforme necessário
          navigation.navigate('Home');
        } else {
          console.error('Token de acesso indefinido');
        }
      } else {
        // Exiba uma mensagem de erro ou alerta caso a autenticação falhe
        alert('Credenciais inválidas');
      }
    } catch (error) {
      console.error('Erro ao autenticar:', error);
    }
  };

  return (
    <View style={styles.container}>
      <Image source={require("../imagens/logo_netcomet.png")} style={styles.logo}/>
      <StatusBar backgroundColor="#4169E1" barStyle="light-content" />
      <Text style={styles.title}>Entrar</Text>
      <TextInput
        style={styles.input}
        placeholder="Usuario"
        onChangeText={text => setUsername(text)}
        value={username}
      />

      <TextInput
        style={styles.input}
        placeholder="Senha"
        secureTextEntry
        onChangeText={text => setPassword(text)}
        value={password}
      />

      <TouchableOpacity style={styles.buttonContainer} onPress={handleLogin}>
        <Text style={styles.buttonText}>Entrar</Text>
      </TouchableOpacity>
    </View>
  );
};

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#4169E1',
    justifyContent: 'center',
    alignItems: 'center',
    paddingTop: 10,
  },
  title: {
    fontSize: 24,
    marginBottom: 10,
    color: 'white',
  },
  input: {
    borderRadius: 5,
    backgroundColor: 'white',
    height: 50,
    width: '80%',
    borderColor: 'black',
    borderWidth: 1,
    marginBottom: 25,
    paddingLeft: 8,
  },
  buttonContainer: {
    height: 50,
    width: '80%',
    backgroundColor: '#32CD32',
    padding: 10,
    alignItems: 'center',
    borderRadius: 4,
    marginBottom: 10,
  },
  buttonText: {
    color: 'white',
    fontSize: 18,
    textAlign: 'center',
  },
  logo:{
    width: "70%",
    Bottom: 50,
    height: 120,
    alignItems: "center",
    justifyContent: "center",
    borderRadius: 8,
    marginBottom: 30,
    textAlign: 'center',
  }
});

export default LoginScreen;
