import { View, TextInput, Text, StyleSheet, TouchableOpacity, KeyboardAvoidingView, ScrollView, Platform } from 'react-native';

const DetalhesCarrosScreen = ({ route }) => {
  const { dadosCarros } = route.params;

  return (
    <View style={styles.container}>
      <KeyboardAvoidingView behavior={Platform.OS === 'ios' ? 'padding' : 'height'} style={styles.container}>
        <ScrollView contentContainerStyle={styles.scrollViewContent} keyboardShouldPersistTaps="handled">

            <Text style={styles.input}>Data:</Text>
                <TextInput
                style={styles.input}
                placeholder="Informe o nome do funcionário"
                value={dadosCarros.data}
            />

            <Text style={styles.input}>Nome:</Text>
                <TextInput
                style={styles.input}
                placeholder="Informe o nome do funcionário"
                value={dadosCarros.nome}
            />

            <Text style={styles.input}>Km Inicial:</Text>
                <TextInput
                style={styles.input}
                placeholder="Informe o nome do funcionário"
                value={dadosCarros.kmInicial}
            />

            <Text style={styles.input}>Km Final:</Text>
                <TextInput
                style={styles.input}
                placeholder="Informe o nome do funcionário"
                value={dadosCarros.kmFinal}
            />

            <Text style={styles.input}>Hora Saida:</Text>
                <TextInput
                style={styles.input}
                placeholder="Informe o nome do funcionário"
                value={dadosCarros.horaSaida}
            />

            <Text style={styles.input}>Hora Chegada:</Text>
                <TextInput
                style={styles.input}
                placeholder="Informe o nome do funcionário"
                value={dadosCarros.horaChegada}
            />

            <Text style={styles.input}>Serviço:</Text>
                <TextInput
                style={styles.input}
                placeholder="Informe o nome do funcionário"
                value={dadosCarros.tipoServico}
            />

            <Text style={styles.input}>Localidade:</Text>
                <TextInput
                style={styles.input}
                placeholder="Informe o nome do funcionário"
                value={dadosCarros.localidade}
            />

            <Text style={styles.input}>Região:</Text>
                <TextInput
                style={styles.input}
                placeholder="Informe o nome do funcionário"
                value={dadosCarros.regiao}
            />

        </ScrollView>
      </KeyboardAvoidingView>
    </View>
  );
};

const styles = StyleSheet.create({
    container: {
        padding: 16,
        backgroundColor: '#fff',
        elevation: 2,
        //flex: 1,
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
        marginBottom: 20,  // Aumente a margem inferior para adicionar espaço
      },
      addButtonText: {
        color: '#fff',
        fontSize: 16,
      },
    
      backButtonText: {
        color: '#fff',
        fontSize: 16,
      },
      inputContainerObs:{
        height: 150,
        marginBottom: 10,
      },
      inputObs:{
        height: 90,
        borderColor: '#ccc',
        borderWidth: 1,
        marginBottom: 10,
        paddingLeft: 8,
        borderRadius: 4,
      },
      labelObs:{
        marginBottom: 5,
        color: '#333',
      }
    });

export default DetalhesCarrosScreen;
