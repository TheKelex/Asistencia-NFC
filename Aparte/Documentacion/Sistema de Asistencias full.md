# Sistema de Asistencias full

## Planteamiento General

### Problema

El instructor manifiesta la inconformidad con el tiempo que se pierde registrando la asistencia mediante SENA Sofía Plus, reduciendo el tiempo disponible para el desarrollo de la formación.

### Objetivo

Desarrollar un sistema que permita registrar la asistencia de forma rápida y segura mediante tecnología NFC, permitiendo que cada aprendiz gestione su propio registro mientras el instructor supervisa el proceso.

---

# Solución Propuesta

propongo una aplicación web que permita registrar la asistencia mediante carnets con tecnología NFC, reduciendo el tiempo de toma de asistencia y manteniendo el control del instructor. Para la lectura del carnet NFC, el sistema podrá utilizar un lector NFC dedicado o un dispositivo móvil compatible con NFC, según la disponibilidad de la implementación.

Características principales:

* Autenticación de instructores.
* Inicio y finalización de sesiones de asistencia.
* Registro de asistencia mediante carnet NFC.
* Uso de lector NFC dedicado o dispositivo móvil como escáner NFC.
* Un único registro por aprendiz para cada sesión.
* Registro manual para casos excepcionales.
* Historial y generación de reportes.

---

# Funcionamiento General del Sistema

## Inicio de Sesión de Asistencia

### El instructor

1. Inicia sesión.
2. Selecciona la ficha.
3. Selecciona la competencia.
4. Configura el tiempo de tolerancia para el registro de asistencia (por defecto 10 minutos).
5. Inicia la sesión de asistencia.

### El sistema

1. Crea la sesión de asistencia.
2. Habilita el lector NFC o el dispositivo móvil configurado como escáner NFC.
3. Registra como **Presente** a los aprendices que registren su asistencia dentro del tiempo de tolerancia.
4. Registra como **Retardo** a los aprendices que registren su asistencia después del tiempo de tolerancia y antes de finalizar la sesión.
5. Finaliza automáticamente la sesión al cumplirse la duración programada de la jornada académica (por ejemplo, 5 horas y 30 minutos o 6 horas), si el instructor no la ha finalizado previamente.

---

## Registro de Asistencia del Aprendiz

### El aprendiz

1. Acerca su carnet NFC al lector o al dispositivo móvil con NFC.
2. Espera la confirmación del registro.

### El sistema

1. Lee el identificador del carnet.
2. Identifica al aprendiz.
3. Verifica que la sesión esté activa.
4. Verifica que el aprendiz no haya registrado asistencia anteriormente.
5. Registra la fecha y la hora del registro.
6. Confirma el registro.

---

## Registro Manual

En caso de presentarse alguna excepción:

* Olvido del carnet.
* Daño del carnet.
* Falla del lector NFC o del dispositivo móvil utilizado como escáner NFC.

## Gestión de Retardos

Cuando un aprendiz registra su asistencia después del tiempo de tolerancia establecido para la sesión, el sistema:

1. Marca automáticamente el registro como **Retardo**.
2. Calcula el tiempo de retraso.
3. Calcula las horas de formación perdidas de acuerdo con los parámetros configurados por la institución.
4. Almacena esta información en el historial de asistencia del aprendiz.

Si un aprendiz no registra su asistencia antes de que la sesión sea finalizada (manual o automáticamente), el sistema registrará su estado como **Ausente**.

### El instructor

1. Selecciona el aprendiz.
2. Registra manualmente la asistencia.
3. Selecciona el motivo del registro.

### El sistema

1. Registra la asistencia.
2. Almacena el motivo del registro manual.
3. Registra el instructor, la fecha y la hora de la acción.

---

# Seguridad

## Registro Único

Cada aprendiz solo podrá registrar una asistencia por sesión.

Esto evita:

* Registros duplicados.
* Manipulación de estadísticas.

## Control de Sesiones

Solo podrán registrarse asistencias mientras la sesión permanezca activa.

Esto evita:

* Registros fuera del horario de clase.
* Registros posteriores al cierre de la sesión.

## Auditoría de Registros Manuales

Cada registro manual almacenará:

* Instructor responsable.
* Fecha.
* Hora.
* Motivo del registro.

Esto garantiza la trazabilidad de las acciones realizadas por el instructor.

---

# Riesgos Identificados

## Olvido del carnet NFC

**Solución**

* Registro manual autorizado por el instructor.

---

## Daño del carnet NFC

**Solución**

* Registro manual autorizado por el instructor.

---

## Falla del lector NFC o del dispositivo móvil

**Solución**

* Continuar el registro mediante el mecanismo manual.

---

## Intento de registrar dos veces la asistencia

**Solución**

* El sistema rechazará registros duplicados.

## Aprendiz que llega tarde

**Solución**

- El sistema clasificará automáticamente el registro como **Retardo** cuando el tiempo de tolerancia haya expirado.
- El sistema calculará el tiempo de retraso y las horas de formación perdidas.
- El instructor podrá modificar el estado del registro cuando exista una justificación válida.
---

# Requisitos Funcionales

* RF-01. El instructor debe poder iniciar una sesión de asistencia.
* RF-02. El sistema debe habilitar el lector NFC o el dispositivo móvil configurado como escáner NFC durante la sesión activa.
* RF-03. El aprendiz debe registrar su asistencia acercando su carnet NFC al lector o al dispositivo móvil.
* RF-04. El sistema debe identificar automáticamente al aprendiz mediante el identificador único del carnet.
* RF-05. El sistema debe registrar automáticamente la fecha y la hora del registro de asistencia.
* RF-06. El sistema debe impedir registrar más de una asistencia por sesión.
* RF-07. El sistema debe mostrar una confirmación del registro exitoso.
* RF-08. El instructor debe poder modificar o anular un registro de asistencia cuando sea necesario.
* RF-09. El sistema debe generar reportes de asistencia por ficha, aprendiz y fecha.
* RF-10. El instructor debe poder registrar manualmente la asistencia de un aprendiz cuando no sea posible realizar el registro mediante NFC.
* RF-11. El sistema debe solicitar el motivo del registro manual (olvido del carnet, daño del carnet o falla del lector NFC o del dispositivo móvil).
* RF-12. En caso de falla del lector NFC o del dispositivo móvil, el sistema debe permitir continuar el registro de asistencia mediante el mecanismo manual.
* RF-13. El instructor debe poder finalizar la sesión de asistencia.
* RF-14. El sistema solo debe permitir el registro de asistencias mientras la sesión se encuentre activa.
* RF-15. El instructor debe poder configurar el tiempo de tolerancia para el registro de asistencia al iniciar la sesión.
* RF-16. El sistema debe clasificar automáticamente el estado de asistencia como Presente, Retardo o Ausente según el momento en que se registre la asistencia.
* RF-17. El sistema debe calcular automáticamente el tiempo de retraso de cada aprendiz.
* RF-18. El sistema debe calcular automáticamente las horas de formación perdidas de acuerdo con las reglas institucionales configuradas.
* RF-19. El instructor debe poder modificar el estado de asistencia cuando exista una justificación válida.
* RF-20. El sistema debe finalizar automáticamente la sesión de asistencia al cumplirse la duración programada de la jornada académica si el instructor no la ha finalizado previamente.

---

# Requisitos No Funcionales

* RNF-01. El tiempo de lectura del carnet NFC no debe superar 1 segundo.
* RNF-02. El sistema debe registrar correctamente al menos el 99 % de las lecturas NFC.
* RNF-03. Solo el instructor autorizado podrá iniciar o finalizar una sesión de asistencia.
* RNF-04. La información de asistencia deberá almacenarse de forma segura.
* RNF-05. El sistema deberá funcionar incluso con grupos de hasta 40 aprendices sin degradar su rendimiento.
* RNF-06. Todo registro manual deberá almacenar el instructor que realizó la acción, la fecha y la hora del registro.
* RNF-07. El sistema debe garantizar la conservación de la información registrada durante fallas del lector NFC o del dispositivo móvil utilizado como escáner NFC.
