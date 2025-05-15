%% Diagrama de Casos de Uso
%% Título: Módulo de Análisis Predictivo de Usuario
left to right direction
actor Usuario
actor SistemaExterno <<system>>
actor Administrador

rectangle "Módulo de Análisis Predictivo" {
  Usuario -- (Interactuar con el Sistema)
  SistemaExterno -- (Recibir Predicciones)
  Administrador -- (Gestionar Modelos Predictivos)
  Administrador -- (Configurar Parámetros de Análisis)
  Administrador -- (Visualizar Resultados del Análisis)
  (Interactuar con el Sistema) .> (Generar Datos de Comportamiento) : include
  (Generar Datos de Comportamiento) -- Usuario
  (Recibir Predicciones) <. (Enviar Predicciones) : include
  (Enviar Predicciones) -- SistemaExterno
  (Gestionar Modelos Predictivos) <. (Entrenar Modelo) : include
  (Gestionar Modelos Predictivos) <. (Evaluar Modelo) : include
  (Gestionar Modelos Predictivos) <. (Desplegar Modelo) : include
  (Configurar Parámetros de Análisis) -- Administrador
  (Visualizar Resultados del Análisis) -- Administrador
  (Generar Predicciones) .> (Consultar Datos Históricos) : include
  (Generar Predicciones) .> (Aplicar Modelo Predictivo) : include
  (Interactuar con el Sistema) --> (Generar Predicciones)
}
