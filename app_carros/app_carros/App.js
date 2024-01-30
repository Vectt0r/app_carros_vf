import React from 'react';
import { createStackNavigator } from '@react-navigation/stack';
import { NavigationContainer } from '@react-navigation/native';
import LoginScreen from './src/screens/LoginScreen';
import HomeScreen from './src/screens/HomeScreen';
import CarForm from './src/screens/CarForm';
import DetalhesCarrosScreen from './src/screens/DetalhesCarrosScreen';

const Stack = createStackNavigator();

const App = () => {
  return (
    <NavigationContainer>
      <Stack.Navigator initialRouteName="Login">
        <Stack.Screen
          name="Login"
          component={LoginScreen}
          options={{
            headerShown: false, // Oculta o cabeçalho apenas na tela de login
          }}
        />
        <Stack.Screen name="Home" component={HomeScreen} />
        <Stack.Screen name="Iniciar Corrida" component={CarForm}/>
        <Stack.Screen name="Finalizar" component={DetalhesCarrosScreen} />
      </Stack.Navigator>
    </NavigationContainer>
  );
};

export default App;
